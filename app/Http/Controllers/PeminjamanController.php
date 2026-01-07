<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanWarkah;
use App\Models\Warkah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class PeminjamanController extends Controller
{
    /** 🔹 Daftar semua peminjaman */
    public function index(Request $request)
    {
        $query = PeminjamanWarkah::with('warkah');

        // 🔸 Update otomatis status "Terlambat" + sinkron ke tabel warkah
        $terlambatList = PeminjamanWarkah::where('status', 'Dipinjam')
            ->whereDate('batas_peminjaman', '<', now())
            ->get();

        foreach ($terlambatList as $item) {
            $item->update(['status' => 'Terlambat']);
            if ($item->warkah) {
                $item->warkah->update(['status' => 'Terlambat']);
            }
        }

        // 🔸 Filter pencarian dengan normalisasi
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            // ✨ Normalisasi keyword dengan menghapus spasi
            $normalizedSearch = preg_replace('/\s+/', '', $search);

            $query->where(function ($q) use ($search, $normalizedSearch) {
                // Pencarian biasa tanpa normalisasi untuk nama, email, no_hp
                $q->where('nama_peminjam', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('no_hp', 'like', '%' . $search . '%')
                    // Pencarian dengan normalisasi untuk data warkah
                    ->orWhereHas('warkah', function ($q2) use ($normalizedSearch) {
                        $q2->whereRaw("REPLACE(REPLACE(kode_klasifikasi, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedSearch}%"])
                            ->orWhereRaw("REPLACE(REPLACE(uraian_informasi_arsip, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedSearch}%"])
                            ->orWhereRaw("REPLACE(REPLACE(nomor_item_arsip, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedSearch}%"]);
                    });
            });
        }

        // 🔸 Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 🔸 Urutkan: Terlambat > Dipinjam > Dikembalikan
        $peminjaman = $query
            ->orderByRaw("CASE 
                WHEN status = 'Terlambat' THEN 1 
                WHEN status = 'Dipinjam' THEN 2 
                WHEN status = 'Dikembalikan' THEN 3 
                ELSE 4 
            END")
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        // Statistik untuk card
        $totalDipinjam = PeminjamanWarkah::where('status', 'Dipinjam')->count();
        $totalTerlambat = PeminjamanWarkah::where('status', 'Terlambat')->count();
        $totalDikembalikan = PeminjamanWarkah::where('status', 'Dikembalikan')->count();
        return view('peminjaman.index', compact(
            'peminjaman',
            'totalDipinjam',      // ← TAMBAHKAN INI
            'totalTerlambat',     // ← TAMBAHKAN INI
            'totalDikembalikan'   // ← TAMBAHKAN INI
        ));
    }

    /** 🔹 Simpan data peminjaman baru */
   /** 🔹 Simpan data peminjaman baru */
            /** 🔹 Simpan data peminjaman baru */
public function store(Request $request)
{
    $validated = $request->validate([
        'nama_peminjam' => 'required|string|max:255',
        'no_hp' => 'required|string|max:20',
        'email' => 'required|email|max:255',
        'tanggal_pinjam' => 'required|date',
        'tujuan_pinjam' => 'required|string',
        'batas_peminjaman' => 'required|date',
        'nomor_nota_dinas' => 'required|string|max:255',
        'file_nota_dinas' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'uraian' => 'nullable|string|max:1000',  // Sesuai nama kolom di DB
    ]);

    $warkah = Warkah::find($request->id_warkah);

    if ($warkah->isDipinjam()) {
        return back()->withErrors(['id_warkah' => 'Warkah ini sedang dipinjam oleh orang lain']);
    }

    // 🔸 Upload file nota dinas
    $fileNotaDinasPath = null;
    if ($request->hasFile('file_nota_dinas')) {
        $fileNotaDinasPath = $request->file('file_nota_dinas')->store('nota_dinas', 'public');
    }

    $validated['id_warkah'] = $request->id_warkah;
    $validated['file_nota_dinas'] = $fileNotaDinasPath;
    
    PeminjamanWarkah::create($validated);
    $warkah->update(['status' => 'Dipinjam']);

    return redirect()->route('peminjaman.index')
        ->with('success', 'Data peminjaman berhasil ditambahkan');
}
    /** 🔹 Tampilkan detail peminjaman (halaman show) */
    public function show($id)
    {
        $peminjaman = PeminjamanWarkah::with('warkah')->findOrFail($id);
        return view('peminjaman.show', compact('peminjaman'));
    }

    /** 🔹 Proses pengembalian warkah */
    public function kembalikan(Request $request, $id)
    {
        $peminjaman = PeminjamanWarkah::with('warkah')->findOrFail($id);

        $validated = $request->validate([
            'tanggal_pengembalian' => 'nullable|date',
            'kondisi' => 'required|in:Baik,Rusak',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'catatan' => 'nullable|string|max:1000',
        ]);

        // 🔸 Upload bukti jika ada
        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')->store('bukti_pengembalian', 'public');
        }

        // 🔸 Update peminjaman
        $peminjaman->update([
            'status' => 'Dikembalikan',
            'tanggal_kembali' => $validated['tanggal_pengembalian'] ?? now(),
            'kondisi' => $validated['kondisi'],
            'bukti' => $buktiPath,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        // 🔸 Update status master warkah
        if ($peminjaman->warkah) {
            $statusBaru = match ($validated['kondisi']) {
                // 'Hilang' => 'Hilang',
                'Rusak' => 'Rusak',
                'Baik' => 'Tersedia', // <--- lebih eksplisit
            };

            $peminjaman->warkah->update(['status' => $statusBaru]);
        }

        return redirect()->back()->with('success', 'Warkah berhasil dikembalikan dan status telah diperbarui!');
    }

    /** 🔹 Ambil data warkah yang masih tersedia */
    public function getAvailableWarkah(Request $request)
    {
        $search = $request->get('search', '');

        // Ambil hanya warkah yang statusnya benar-benar "Tersedia"
        $query = Warkah::where('status', 'Tersedia');

        // Tambahkan filter pencarian jika ada input
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('kode_klasifikasi', 'LIKE', "%{$search}%")
                    ->orWhere('uraian_informasi_arsip', 'LIKE', "%{$search}%")
                    ->orWhere('ruang_penyimpanan_rak', 'LIKE', "%{$search}%")
                    ->orWhere('nomor_item_arsip', 'LIKE', "%{$search}%")
                    ->orWhere('kurun_waktu_berkas', 'LIKE', "%{$search}%")
                    ->orWhere('lokasi', 'LIKE', "%{$search}%")
                    ->orWhere('no_boks_definitif', 'LIKE', "%{$search}%")
                    ->orWhere('no_folder', 'LIKE', "%{$search}%");
            });
        }

        $warkah = $query->select(
            'id',
            'kode_klasifikasi',
            'nomor_item_arsip',
            'uraian_informasi_arsip',
            'ruang_penyimpanan_rak',
            'kurun_waktu_berkas',
            'lokasi',
            'status'
        )
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json($warkah);
    }
}
