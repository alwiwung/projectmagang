<?php

namespace App\Http\Controllers;

use App\Models\Warkah;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WarkahExport;
use App\Imports\WarkahImport;

class WarkahController extends Controller
{
    /**
     * Tampilkan daftar arsip
     */
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $filters = $request->only(['kurun_waktu_berkas', 'ruang_penyimpanan_rak', 'kode_klasifikasi', 'status']);
        $showDeleted = $request->get('show_deleted', false);

        $query = Warkah::query();

        if ($showDeleted) {
            $query->onlyTrashed();
        }

        if ($keyword) {
            $query->search($keyword);
        }

        if (!empty(array_filter($filters))) {
            $query->filter($filters);
        }

        $warkah = $query->orderBy('created_at', 'desc')->paginate(15);

        // Ambil daftar untuk dropdown filter
        $tahunList = Warkah::select('kurun_waktu_berkas')->distinct()->pluck('kurun_waktu_berkas')->sort()->reverse();
        $lokasiList = Warkah::select('ruang_penyimpanan_rak')->distinct()->pluck('ruang_penyimpanan_rak')->sort();
        $klasifikasiList = Warkah::select('kode_klasifikasi')->distinct()->pluck('kode_klasifikasi')->sort();

        return view('warkah.index', compact(
            'warkah', 'keyword', 'filters', 'tahunList', 'lokasiList', 'klasifikasiList', 'showDeleted'
        ));
    }

    /**
     * Form tambah data
     */
    public function create()
    {
        return view('warkah.create');
    }

    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_klasifikasi' => 'required|string|max:50',
            'jenis_arsip_vital' => 'required|string|max:100',
            'nomor_item_arsip' => 'nullable|string|max:100',
            'uraian_informasi_arsip' => 'required|string',
            'kurun_waktu_berkas' => 'nullable|string|max:50',
            'media' => 'nullable|string|max:50',
            'jumlah' => 'nullable|string|max:50',
            'aktif' => 'nullable|string|max:50',
            'inaktif' => 'nullable|string|max:50',
            'tingkat_perkembangan' => 'nullable|string|max:100',
            'ruang_penyimpanan_rak' => 'nullable|string|max:100',
            'no_boks_definitif' => 'nullable|string|max:100',
            'no_folder' => 'nullable|string|max:100',
            'metode_perlindungan' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            $validated['status'] = 'Tersedia';
            if (auth()->check()) {
                $validated['created_by'] = auth()->id();
            }

            Warkah::create($validated);

            return redirect()->route('warkah.index')->with('success', 'Data arsip berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('❌ Error saat menyimpan arsip: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Lihat detail arsip
     */
    public function show(Warkah $warkah)
    {
        return view('warkah.show', compact('warkah'));
    }

    /**
     * Form edit arsip
     */
    public function edit(Warkah $warkah)
    {
        return view('warkah.edit', compact('warkah'));
    }

    /**
     * Update data arsip
     */
    public function update(Request $request, Warkah $warkah)
    {
        $validated = $request->validate([
            'kode_klasifikasi' => 'required|string|max:50',
            'jenis_arsip_vital' => 'required|string|max:100',
            'nomor_item_arsip' => 'nullable|string|max:100',
            'uraian_informasi_arsip' => 'required|string',
            'kurun_waktu_berkas' => 'nullable|string|max:50',
            'media' => 'nullable|string|max:50',
            'jumlah' => 'nullable|string|max:50',
            'aktif' => 'nullable|string|max:50',
            'inaktif' => 'nullable|string|max:50',
            'tingkat_perkembangan' => 'nullable|string|max:100',
            'ruang_penyimpanan_rak' => 'nullable|string|max:100',
            'no_boks_definitif' => 'nullable|string|max:100',
            'no_folder' => 'nullable|string|max:100',
            'metode_perlindungan' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            if (auth()->check()) {
                $validated['updated_by'] = auth()->id();
            }

            $warkah->update($validated);

            return redirect()->route('warkah.index')->with('success', 'Data arsip berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Export ke Excel
     */
        public function export(Request $request)
    {
        // ambil filter dari request
        $filters = [
            'kurun_waktu_berkas' => $request->kurun_waktu_berkas,
            'ruang_penyimpanan_rak' => $request->ruang_penyimpanan_rak,
            'kode_klasifikasi' => $request->kode_klasifikasi,
            'status' => $request->status,
            'keyword' => $request->keyword,
        ];

        $fileName = 'data_warkah_' . now()->format('Y_m_d_His') . '.xlsx';
        return Excel::download(new WarkahExport($filters), $fileName);
    }

    /**
     * Import dari Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new WarkahImport, $request->file('file'));
            return redirect()->route('warkah.index')->with('success', '✅ Data berhasil diimport!');
        } catch (\Exception $e) {
            \Log::error('❌ Error import Excel: ' . $e->getMessage());
            return back()->with('error', 'Gagal import file: ' . $e->getMessage());
        }
    }
}
