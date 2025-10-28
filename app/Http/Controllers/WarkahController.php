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

    // 🔍 Cek apakah ada pencarian
    if ($keyword) {
        if (str_starts_with($keyword, '#')) {
            // Jika keyword diawali tanda #, hanya cari berdasarkan ID (tepat, bukan LIKE)
            $id = ltrim($keyword, '#');
            if (is_numeric($id)) {
                $query->where('id', $id);
            }
        } else {
            // ✨ PERBAIKAN: Normalisasi keyword dengan menghapus spasi
            $normalizedKeyword = preg_replace('/\s+/', '', $keyword);
            
            // Pencarian dengan normalisasi (menghapus spasi dari kolom database juga)
            $query->where(function($q) use ($normalizedKeyword) {
                $q->whereRaw("REPLACE(REPLACE(kode_klasifikasi, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedKeyword}%"])
                  ->orWhereRaw("REPLACE(REPLACE(uraian_informasi_arsip, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedKeyword}%"])
                  ->orWhereRaw("REPLACE(REPLACE(nomor_item_arsip, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedKeyword}%"])
                  ->orWhereRaw("REPLACE(REPLACE(jenis_arsip_vital, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedKeyword}%"]);
            });
        }
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
            'warkah',
            'keyword',
            'filters',
            'tahunList',
            'lokasiList',
            'klasifikasiList',
            'showDeleted'
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
            'jenis_arsip_vital' => 'required|string|max:200',
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

        return redirect()->route('warkah.index')->with('success', 'Data Warkah berhasil ditambahkan.');
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
            'jenis_arsip_vital' => 'required|string|max:200',
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

    try {
        $file = $request->file('file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        // 🧭 Deteksi baris header utama
        $headerRowIndex = null;
        foreach ($rows as $i => $row) {
            $rowText = strtolower(implode(' ', $row));
            if (str_contains($rowText, 'kode klasifikasi') || str_contains($rowText, 'uraian informasi arsip')) {
                $headerRowIndex = $i;
                break;
            }
        }

        if (!$headerRowIndex) {
            return back()->withErrors([
                'file' => '❌ Header tidak ditemukan di file Excel. Pastikan ada kolom seperti "Kode Klasifikasi" dan "Uraian Informasi Arsip".'
            ]);
        }

        // 🧩 Gabungkan dua baris header (header utama + subheader)
        $headerRow = $rows[$headerRowIndex];
        $nextRow = $rows[$headerRowIndex + 1] ?? [];

        $mergedHeaderRow = [];
        foreach ($headerRow as $col => $val) {
            $top = trim($val ?? '');
            $bottom = trim($nextRow[$col] ?? '');
            $merged = trim($top . ' ' . $bottom); // gabung atas + bawah
            $mergedHeaderRow[$col] = $merged;
        }

        // 🔠 Normalisasi nama header
        $headers = [];
        foreach ($mergedHeaderRow as $key => $val) {
            $normalized = strtolower(trim(preg_replace('/\s+/', ' ', $val ?? '')));
            $headers[$key] = $normalized;
        }

        // ✅ Header wajib minimal
        $requiredHeaders = ['kode klasifikasi', 'uraian informasi arsip'];
        $missing = [];
        foreach ($requiredHeaders as $req) {
            if (!in_array($req, $headers)) {
                $missing[] = $req;
            }
        }

        if (!empty($missing)) {
            return back()->withErrors([
                'file' => '❌ Format file tidak sesuai. Header berikut tidak ditemukan: ' . implode(', ', $missing)
            ]);
        }

        $inserted = 0;
        $duplicates = 0;
        $skipped = 0;

        // 🔁 Loop data isi
        for ($i = $headerRowIndex + 2; $i <= count($rows); $i++) {
            $row = $rows[$i];
            if (!array_filter($row)) continue;

            $data = [];
            foreach ($headers as $col => $headerName) {
                $data[$headerName] = trim($row[$col] ?? '');
            }

            // Lewati jika kosong
            if (empty($data['uraian informasi arsip'])) {
                $skipped++;
                continue;
            }

            // 🧠 Fuzzy match untuk lokasi simpan dan folder
            $ruang = null;
            $noBoks = null;
            $noFolder = null;

            foreach ($data as $key => $value) {
                if (preg_match('/ruang|rak/i', $key)) $ruang = $value;
                if (preg_match('/boks|box/i', $key)) $noBoks = $value;
                if (preg_match('/folder/i', $key)) $noFolder = $value;
            }

            // Cek duplikat
            $exists = Warkah::where('kode_klasifikasi', $data['kode klasifikasi'] ?? null)
                ->where('uraian_informasi_arsip', $data['uraian informasi arsip'] ?? null)
                ->exists();

            if ($exists) {
                $duplicates++;
                continue;
            }

            // Simpan
            Warkah::create([
                'kode_klasifikasi'        => $data['kode klasifikasi'] ?? null,
                'jenis_arsip_vital'       => $data['jenis arsip vital'] ?? null,
                'nomor_item_arsip'        => $data['nomor item arsip'] ?? null,
                'uraian_informasi_arsip'  => $data['uraian informasi arsip'] ?? null,
                'kurun_waktu_berkas'      => $data['kurun waktu berkas'] ?? null,
                'media'                   => $data['media'] ?? null,
                'jumlah'                  => $data['jumlah'] ?? null,
                'jangka_simpan_aktif'     => $data['jangka simpan aktif'] ?? $data['aktif'] ?? null,
                'jangka_simpan_inaktif'   => $data['jangka simpan inaktif'] ?? $data['inaktif'] ?? null,
                'tingkat_perkembangan'    => $data['tingkat perkembangan'] ?? null,
                'ruang_penyimpanan_rak'   => $ruang,
                'no_boks_definitif'       => $noBoks,
                'no_folder'               => $noFolder,
                'metode_perlindungan'     => $data['metode perlindungan'] ?? null,
                'keterangan'              => $data['keterangan'] ?? null,
                'status'                  => $data['status'] ?? 'Tersedia',
                'created_by'              => auth()->id(),
            ]);

            $inserted++;
        }

        return back()->with('success', "✅ Import selesai. Data baru: {$inserted}, Duplikat: {$duplicates}, Dilewati: {$skipped}.");

    } catch (\Exception $e) {
        return back()->withErrors([
            'file' => 'Terjadi kesalahan saat membaca file: ' . $e->getMessage(),
        ]);
    }
}

}