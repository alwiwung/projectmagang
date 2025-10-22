<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permintaan;
use App\Models\Warkah;
use Illuminate\Support\Str;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PermintaanController extends Controller
{
    /**
     * Tampilkan semua data permintaan
     */
    public function index()
    {
        $permintaan = Permintaan::with('warkah')->latest()->get();

        $years = Permintaan::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('permintaan.index', compact('permintaan', 'years'));
    }

    /**
     * Form tambah permintaan baru
     */
    public function create()
    {
        $warkah = Warkah::orderBy('uraian_informasi_arsip', 'asc')->get();
        return view('permintaan.create', compact('warkah'));
    }

    /**
     * Simpan permintaan baru
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'warkah_id' => 'required|exists:master_warkah,id',
            'pemohon' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'tanggal_permintaan' => 'required|date',
            'jumlah_salinan' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        $warkah = Warkah::findOrFail($data['warkah_id']);

        Permintaan::create([
            'warkah_id' => $warkah->id,
            'uraian_informasi_arsip' => $warkah->uraian_informasi_arsip,
            'pemohon' => $data['pemohon'],
            'instansi' => $data['instansi'] ?? '-',
            'tanggal_permintaan' => $data['tanggal_permintaan'],
            'jumlah_salinan' => $data['jumlah_salinan'],
            'catatan' => $data['catatan'] ?? null,
            'status' => 'baru',
            'barcode_path' => Str::uuid(),
        ]);

        return redirect()->route('permintaan.index')
            ->with('success', 'Permintaan salinan berhasil disimpan.');
    }

    /**
     * Ubah status permintaan
     */
    public function updateStatus($id)
    {
        $permintaan = Permintaan::findOrFail($id);

        switch ($permintaan->status) {
            case 'baru':
                $permintaan->status = 'diproses';
                break;
            case 'diproses':
                $permintaan->status = 'selesai';
                break;
        }

        $permintaan->save();

        return back()->with('success', 'Status permintaan diperbarui menjadi: ' . $permintaan->status);
    }

   public function show($id)
{
    $permintaan = Permintaan::with('warkah')->findOrFail($id);

    $textToEncode = $permintaan->nomor_perm ?? 'Data tidak tersedia'; // fallback

    $qrCode = QrCode::size(200)->generate($textToEncode);

    return view('permintaan.detail', compact('permintaan', 'qrCode'));
}


public function cetakPDF($id)
{
    $permintaan = Permintaan::with('warkah')->findOrFail($id);

    $pdf = PDF::loadView('permintaan.pdf', compact('permintaan'))
        ->setPaper('a4', 'portrait');

    return $pdf->download('PermintaanSalinan_'.$permintaan->id.'.pdf');
}

}
