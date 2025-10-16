<?php

namespace App\Http\Controllers;

use App\Models\Warkah;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WarkahExport;
use App\Imports\WarkahImport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

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

        $tahunList = Warkah::select('kurun_waktu_berkas')
            ->distinct()
            ->pluck('kurun_waktu_berkas')
            ->map(fn($tahun) => trim($tahun))           // Hilangkan spasi di depan/akhir
            ->filter(fn($tahun) => $tahun !== '')       // Buang yang kosong
            ->sort()
            ->reverse()
            ->values();    
        $lokasiList = Warkah::select('ruang_penyimpanan_rak')
            ->distinct()
            ->pluck('ruang_penyimpanan_rak')
            ->map(fn($lokasi) => trim($lokasi))
            ->filter(fn($lokasi) => $lokasi !== '')
            ->sort()
            ->values();

        $klasifikasiList = Warkah::select('kode_klasifikasi')
            ->distinct()
            ->pluck('kode_klasifikasi')
            ->map(fn($kode) => trim($kode))
            ->filter(fn($kode) => $kode !== '')
            ->sort()
            ->values();

        return view('warkah.index', compact(
            'warkah', 'keyword', 'filters', 'tahunList', 'lokasiList', 'klasifikasiList', 'showDeleted'
        ));
    }

    public function create()
    {
        return view('warkah.create');
    }

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

        $validated['status'] = 'Tersedia';
        if (auth()->check()) {
            $validated['created_by'] = auth()->id();
        }

        Warkah::create($validated);

        return redirect()->route('warkah.index')->with('success', 'Data arsip berhasil ditambahkan.');
    }

    public function show(Warkah $warkah)
    {
        return view('warkah.show', compact('warkah'));
    }

    public function edit(Warkah $warkah)
    {
        return view('warkah.edit', compact('warkah'));
    }

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

        if (auth()->check()) {
            $validated['updated_by'] = auth()->id();
        }

        $warkah->update($validated);

        return redirect()->route('warkah.index')->with('success', 'Data arsip berhasil diperbarui.');
    }

    public function export(Request $request)
    {
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        // Deteksi baris header
        $headerRowIndex = null;
        foreach ($rows as $i => $row) {
            $rowText = strtolower(implode(' ', $row));
            if (str_contains($rowText, 'kode klasifikasi') || str_contains($rowText, 'lokasi simpan')) {
                $headerRowIndex = $i;
                break;
            }
        }

        if (!$headerRowIndex) {
            return back()->with('error', '❌ Header tidak ditemukan di file Excel.');
        }

       // 🔠 Normalisasi nama header
$headers = [];
foreach ($rows[$headerRowIndex] as $key => $val) {
    $headers[$key] = strtolower(trim(preg_replace('/\s+/', ' ', $val ?? '')));
}

// 🔧 Jika ada subheader di bawah (misal "Ruang Penyimpanan / Rak" atau "No. Folder")
$nextRow = $rows[$headerRowIndex + 1] ?? [];
foreach ($nextRow as $key => $val) {
    $val = strtolower(trim(preg_replace('/\s+/', ' ', $val ?? '')));
    if ($headers[$key] === 'lokasi simpan' && $val) {
        // Gabungkan dengan subheader, contoh: "lokasi simpan - ruang penyimpanan/rak"
        $headers[$key] = $val;
    } elseif (empty($headers[$key]) && $val) {
        // Jika header kosong tapi subheader ada (kemungkinan besar "no folder")
        $headers[$key] = $val;
    }
}

        for ($i = $headerRowIndex + 1; $i <= count($rows); $i++) {
            $row = $rows[$i];
            if (!array_filter($row)) continue;

            $data = [];
            foreach ($headers as $col => $headerName) {
                $data[$headerName] = trim($row[$col] ?? '');
            }

   
                        $ruang = $data['ruang penyimpanan/rak']
                    ?? $data['ruang penyimpanan / rak']
                    ?? $data['ruang penyimpanan/ rak'] // Tambahkan ini
                    ?? $data['ruang penyimpanan /rak']
                    ?? $data['ruang penyimpanan']
                    ?? $data['lokasi simpan']
                    ?? null;
                            $noBoks = $data['no. boks definitif']
                                ?? $data['no boks definitif']
                                ?? $data['no boks']
                                ?? null;

                            $noFolder = $data['no. folder']
                                ?? $data['no folder']
                                ?? $data['folder']
                                ?? null;

            Warkah::create([
                'nomor_urut'             => $data['nomor urut'] ?? null,
                'kode_klasifikasi'       => $data['kode klasifikasi'] ?? null,
                'jenis_arsip_vital'      => $data['jenis arsip vital'] ?? null,
                'nomor_item_arsip'       => $data['nomor item arsip'] ?? null,
                'uraian_informasi_arsip' => $data['uraian informasi arsip'] ?? null,
                'kurun_waktu_berkas'     => $data['kurun waktu berkas'] ?? null,
                'media'                  => $data['media'] ?? null,
                'jumlah'                 => $data['jumlah'] ?? null,
                'aktif'                  => $data['aktif'] ?? null,
                'inaktif'                => $data['inaktif'] ?? null,
                'tingkat_perkembangan'   => $data['tingkat perkembangan'] ?? null,
                'ruang_penyimpanan_rak'  => $ruang,
                'no_boks_definitif'      => $noBoks,
                'no_folder'              => $noFolder,
                'metode_perlindungan'    => $data['metode perlindungan'] ?? null,
                'keterangan'             => $data['keterangan'] ?? null,
            ]);
        }

        return back()->with('success', '✅ Import Data Berhasil!');
    }
}
