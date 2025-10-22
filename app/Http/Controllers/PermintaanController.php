<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG; // untuk barcode
use Illuminate\Support\Facades\Storage;

class PermintaanController extends Controller
{
    /**
     * Menampilkan daftar permintaan salinan warkah dengan filter opsional.
     */
    public function index(Request $request)
    {
        $query = Permintaan::query();

        // Filter berdasarkan tahun permintaan
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_permintaan', $request->tahun);
        }

        // Filter berdasarkan kata kunci (pemohon / instansi)
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('pemohon', 'like', "%{$request->keyword}%")
                  ->orWhere('instansi', 'like', "%{$request->keyword}%");
            });
        }

        // Ambil data terbaru dengan pagination
        $permintaan = $query->latest()->paginate(15);

        // Daftar tahun untuk dropdown filter
        $years = range(2015, now()->year);

        return view('permintaan.index', compact('permintaan', 'years'));
    }

    /**
     * Form membuat permintaan baru.
     */
    public function create()
    {
        return view('permintaan.create');
    }

    /**
     * Simpan permintaan baru ke database + generate barcode.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'pemohon' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'tanggal_permintaan' => 'required|date',
            'kode_warkah' => 'nullable|string',
            'jumlah_salinan' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        $data['status'] = 'baru';
        $data['created_by'] = auth()->id() ?? null;

        // Set default tahapan (array)
        $data['tahapan'] = [
            '1' => 'Nota Dinas diterima',
            '2' => 'Disposisi pejabat',
            '3' => 'Pencatatan di spreadsheet',
            '4' => 'Pencarian arsip',
            '5' => 'Fotokopi/Salin',
            '6' => 'Pemberian barcode',
            '7' => 'Balasan nota dinas'
        ];

        // Simpan record ke database
        $permintaan = Permintaan::create($data);

        /**
         * 1) Generate Barcode
         */
        $barcodeValue = 'PSW-' . $permintaan->id . '-' . time();
        $generator = new BarcodeGeneratorPNG();
        $barcodeData = $generator->getBarcode($barcodeValue, $generator::TYPE_CODE_128);

        $path = 'public/barcodes/permintaan_' . $permintaan->id . '.png';
        Storage::put($path, $barcodeData);

        $permintaan->barcode_path = Storage::url($path);
        $permintaan->save();

        /**
         * 2) Catat ke spreadsheet sederhana (CSV)
         */
        $csvLine = [
            $permintaan->id,
            $permintaan->pemohon,
            $permintaan->instansi,
            $permintaan->tanggal_permintaan,
            $permintaan->kode_warkah,
            $permintaan->jumlah_salinan,
            $permintaan->status,
            now()->toDateTimeString()
        ];

        $csvRow = implode(',', array_map(function ($v) {
            return '"' . str_replace('"', '""', ($v ?? '')) . '"';
        }, $csvLine)) . "\n";

        file_put_contents(storage_path('app/permintaan_log.csv'), $csvRow, FILE_APPEND | LOCK_EX);

        return redirect()->route('permintaan.index')->with('success', 'Permintaan disimpan.');
    }

    /**
     * Tampilkan detail satu permintaan.
     */
    public function show(Permintaan $permintaan)
    {
        return view('permintaan.show', compact('permintaan'));
    }

    /**
     * Form edit permintaan.
     */
    public function edit(Permintaan $permintaan)
    {
        return view('permintaan.edit', compact('permintaan'));
    }

    /**
     * Update data permintaan.
     */
    public function update(Request $request, Permintaan $permintaan)
    {
        $data = $request->validate([
            'pemohon' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'tanggal_permintaan' => 'required|date',
            'kode_warkah' => 'nullable|string',
            'jumlah_salinan' => 'required|integer|min:1',
            'status' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $permintaan->update($data);

        return redirect()->route('permintaan.show', $permintaan)->with('success', 'Data diperbarui.');
    }

    /**
     * Hapus permintaan beserta barcode.
     */
    public function destroy(Permintaan $permintaan)
    {
        // Hapus file barcode jika ada
        if ($permintaan->barcode_path) {
            $file = str_replace('/storage/', 'public/', $permintaan->barcode_path);
            Storage::delete($file);
        }

        $permintaan->delete();

        return redirect()->route('permintaan.index')->with('success', 'Data dihapus.');
    }
}
