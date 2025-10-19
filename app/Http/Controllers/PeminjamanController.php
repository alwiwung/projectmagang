<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanWarkah;
use App\Models\Warkah;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = PeminjamanWarkah::with('warkah');

        // Update status terlambat otomatis
        PeminjamanWarkah::where('status', 'Dipinjam')
            ->whereDate('batas_peminjaman', '<', now())
            ->update(['status' => 'Terlambat']);

        // Filter pencarian - cari di data peminjaman dan data warkah
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_peminjam', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('no_hp', 'like', '%' . $search . '%')
                    ->orWhereHas('warkah', function ($q2) use ($search) {
                        $q2->where('kode_klasifikasi', 'like', '%' . $search . '%')
                            ->orWhere('uraian_informasi_arsip', 'like', '%' . $search . '%')
                            ->orWhere('nomor_item_arsip', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        // Akan dibuat di tutorial berikutnya
        $validated = $request->validate([
            'id_warkah' => 'required|exists:master_warkah,id',
            'nama_peminjam' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'tanggal_pinjam' => 'required|date',
            'tujuan_pinjam' => 'required|string',
            'batas_peminjaman' => 'required|date|after:tanggal_pinjam',
        ]);

        // Cek apakah warkah sedang dipinjam
        $warkah = Warkah::find($request->id_warkah);
        if ($warkah->isDipinjam()) {
            return back()->withErrors(['id_warkah' => 'Warkah ini sedang dipinjam oleh orang lain']);
        }

        // Simpan peminjaman
        $peminjaman = PeminjamanWarkah::create($validated);

        // Update status warkah menjadi Dipinjam
        $warkah->update(['status' => 'Dipinjam']);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil ditambahkan');
    }

    public function show($id)
    {
        $peminjaman = PeminjamanWarkah::with('warkah')->findOrFail($id);
        return response()->json($peminjaman);
    }

    public function kembalikan($id)
    {
        $peminjaman = PeminjamanWarkah::with('warkah')->findOrFail($id);

        // Update status peminjaman
        $peminjaman->status = 'Dikembalikan';
        $peminjaman->tanggal_kembali = now();
        $peminjaman->save();

        // Update status warkah menjadi Tersedia
        if ($peminjaman->warkah) {
            $peminjaman->warkah->update(['status' => 'Tersedia']);
        }

        return redirect()->back()->with('success', 'Warkah berhasil dikembalikan');
    }

    // Method untuk mendapatkan list warkah yang tersedia (untuk dropdown)
    public function getAvailableWarkah()
    {
        $warkah = Warkah::where('status', '!=', 'Dipinjam')
            ->select('id', 'kode_klasifikasi', 'nomor_item_arsip', 'uraian_informasi_arsip')
            ->get();

        return response()->json($warkah);
    }
}
