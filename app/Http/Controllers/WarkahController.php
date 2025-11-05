<?php

namespace App\Http\Controllers;

use App\Models\Warkah;
use App\Models\PeminjamanWarkah;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WarkahExport;
use App\Imports\WarkahImport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
                $query->where(function ($q) use ($normalizedKeyword) {
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
            ->map(fn($tahun) => trim($tahun))
            ->filter(fn($tahun) => $tahun !== '')
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
        $totalWarkah = Warkah::count();
        return view('warkah.index', compact(
            'warkah',
            'keyword',
            'filters',
            'tahunList',
            'lokasiList',
            'klasifikasiList',
            'showDeleted',
            'totalWarkah',
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
            'lokasi' => 'nullable|string|max:100',
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
            'lokasi' => 'nullable|string|max:100',
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

    /**
     * Hapus data warkah
     */
    public function destroy(Warkah $warkah)
    {
        // ✅ Validasi: Hanya warkah dengan status 'Tersedia' yang bisa dihapus
        if ($warkah->status !== 'Tersedia') {
            return redirect()->route('warkah.index')
                ->with('error', "❌ Data warkah tidak dapat dihapus karena sedang berstatus '{$warkah->status}'. Hanya warkah dengan status 'Tersedia' yang dapat dihapus.");
        }

        try {
            $warkah->delete();

            return redirect()->route('warkah.index')
                ->with('success', '✅ Data warkah berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('warkah.index')
                ->with('error', '❌ Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
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

        // ⚠️ STEP 1: VALIDASI AWAL - CEK HEADER PEMINJAMAN DAN PERMINTAAN
        $isPeminjamanFile = false;
        $isPermintaanFile = false;
        $detectedPeminjamanHeaders = [];
        $detectedPermintaanHeaders = [];
        
        foreach ($rows as $row) {
            $rowText = strtolower(implode(' ', array_map('trim', $row)));
            
            // Cek header peminjaman
            if (str_contains($rowText, 'tanggal pinjam') || 
                str_contains($rowText, 'tanggalpinjam') ||
                str_contains($rowText, 'tujuan pinjam') || 
                str_contains($rowText, 'tujuanpinjam') ||
                str_contains($rowText, 'batas peminjaman') ||
                str_contains($rowText, 'bataspeminjaman') ||
                (str_contains($rowText, 'email') && str_contains($rowText, 'peminjam')) ||
                (str_contains($rowText, 'no hp') && str_contains($rowText, 'peminjam')) ||
                (str_contains($rowText, 'no. hp') && str_contains($rowText, 'peminjam'))) {
                
                $isPeminjamanFile = true;
                
                foreach ($row as $cell) {
                    $cellLower = strtolower(trim($cell ?? ''));
                    if (str_contains($cellLower, 'tanggal pinjam') || 
                        str_contains($cellLower, 'tujuan pinjam') ||
                        str_contains($cellLower, 'batas peminjaman') ||
                        (str_contains($cellLower, 'email') && !str_contains($cellLower, 'kode')) ||
                        (str_contains($cellLower, 'no hp') || str_contains($cellLower, 'no. hp'))) {
                        $detectedPeminjamanHeaders[] = trim($cell);
                    }
                }
                break;
            }
            
            // 🆕 CEK HEADER PERMINTAAN
            if (str_contains($rowText, 'nama pemohon') || 
                str_contains($rowText, 'namapemohon') ||
                str_contains($rowText, 'nomor identitas') || 
                str_contains($rowText, 'nomoridentitas') ||
                str_contains($rowText, 'alamat lengkap') ||
                str_contains($rowText, 'alamatlengkap') ||
                str_contains($rowText, 'tanggal permintaan') ||
                str_contains($rowText, 'tanggalpermintaan') ||
                str_contains($rowText, 'jumlah salinan') ||
                str_contains($rowText, 'jumlahsalinan') ||
                (str_contains($rowText, 'instansi') && str_contains($rowText, 'pemohon'))) {
                
                $isPermintaanFile = true;
                
                foreach ($row as $cell) {
                    $cellLower = strtolower(trim($cell ?? ''));
                    if (str_contains($cellLower, 'nama pemohon') || 
                        str_contains($cellLower, 'nomor identitas') ||
                        str_contains($cellLower, 'alamat lengkap') ||
                        str_contains($cellLower, 'tanggal permintaan') ||
                        str_contains($cellLower, 'jumlah salinan') ||
                        str_contains($cellLower, 'instansi') ||
                        str_contains($cellLower, 'nomor telepon')) {
                        $detectedPermintaanHeaders[] = trim($cell);
                    }
                }
                break;
            }
        }
        
        // 🚫 BLOKIR FILE PEMINJAMAN
        if ($isPeminjamanFile) {
            $headerList = !empty($detectedPeminjamanHeaders) 
                ? implode(', ', array_unique($detectedPeminjamanHeaders)) 
                : 'Header Peminjaman';
                
            return back()->withErrors([
                'file' => '
                <div style="padding: 20px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 8px;">
                    <div style="display: flex; align-items: center; margin-bottom: 16px;">
                        <svg style="width: 28px; height: 28px; color: #ff9800; margin-right: 12px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <strong style="font-size: 18px; color: #856404;">❌ File Excel Ditolak</strong>
                    </div>
                    
                    <div style="background: white; padding: 16px; border-radius: 6px; margin-bottom: 16px; border: 1px solid #ffeaa7;">
                        <p style="margin: 0 0 10px 0; color: #333; font-size: 15px; line-height: 1.6;">
                            File Excel yang Anda upload adalah <strong style="color: #d97706;">File Data Peminjaman</strong>, bukan File Data Warkah.
                        </p>
                        <p style="margin: 0; color: #666; font-size: 13px;">
                            Terdeteksi kolom: <code style="background: #ffebee; padding: 3px 8px; border-radius: 3px; color: #c62828; font-weight: 600; border: 1px solid #ef9a9a;">' . $headerList . '</code>
                        </p>
                    </div>
                </div>'
            ]);
        }
        
        // 🚫 BLOKIR FILE PERMINTAAN
        if ($isPermintaanFile) {
            $headerList = !empty($detectedPermintaanHeaders) 
                ? implode(', ', array_unique($detectedPermintaanHeaders)) 
                : 'Header Permintaan';
                
            return back()->withErrors([
                'file' => '
                <div style="padding: 20px; background: #fff3cd; border-left: 4px solid #ff9800; border-radius: 8px;">
                    <div style="display: flex; align-items: center; margin-bottom: 16px;">
                        <svg style="width: 28px; height: 28px; color: #ff6f00; margin-right: 12px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <strong style="font-size: 18px; color: #856404;">❌ File Excel Ditolak</strong>
                    </div>
                    
                    <div style="background: white; padding: 16px; border-radius: 6px; margin-bottom: 16px; border: 1px solid #ffeaa7;">
                        <p style="margin: 0 0 10px 0; color: #333; font-size: 15px; line-height: 1.6;">
                            File Excel yang Anda upload adalah <strong style="color: #e65100;">File Data Permintaan</strong>, bukan File Data Warkah.
                        </p>
                        <p style="margin: 0; color: #666; font-size: 13px;">
                            Terdeteksi kolom: <code style="background: #fff3e0; padding: 3px 8px; border-radius: 3px; color: #e65100; font-weight: 600; border: 1px solid #ffb74d;">' . $headerList . '</code>
                        </p>
                    </div>
                </div>'
            ]);
        }

        // 🧭 STEP 2: Lanjut deteksi header warkah (hanya jika bukan file peminjaman/permintaan)
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
                'file' => '
                <div style="padding: 16px; background: #fee; border-left: 4px solid #dc3545; border-radius: 6px;">
                    <div style="display: flex; align-items: center; margin-bottom: 12px;">
                        <svg style="width: 24px; height: 24px; color: #dc3545; margin-right: 10px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <strong style="font-size: 16px; color: #721c24;">Header Tidak Ditemukan</strong>
                    </div>
                    <p style="margin: 0; color: #721c24; font-size: 14px; line-height: 1.5;">
                        File Excel harus memiliki kolom Yang Sesuai Dengan Excel Warkah
                    </p>
                </div>'
            ]);
        }

        // 🧩 Gabungkan dua baris header
        $headerRow = $rows[$headerRowIndex];
        $nextRow = $rows[$headerRowIndex + 1] ?? [];

        $mergedHeaderRow = [];
        foreach ($headerRow as $col => $val) {
            $top = trim($val ?? '');
            $bottom = trim($nextRow[$col] ?? '');
            
            if (empty($bottom) || strtolower($top) === strtolower($bottom)) {
                $merged = $top;
            } else {
                $merged = trim($top . ' ' . $bottom);
            }
            
            $mergedHeaderRow[$col] = $merged;
        }

        // 🔠 Normalisasi nama header
        $headers = [];
        foreach ($mergedHeaderRow as $key => $val) {
            $normalized = strtolower(trim(preg_replace('/\s+/', ' ', $val ?? '')));
            $headers[$key] = $this->mapHeaderName($normalized);
        }

        // ✅ Validasi header wajib
        $requiredHeaders = ['kode_klasifikasi', 'uraian_informasi_arsip'];
        $missing = [];
        foreach ($requiredHeaders as $req) {
            if (!in_array($req, $headers)) {
                $missing[] = $req;
            }
        }

        if (!empty($missing)) {
            $detectedHeaders = implode(', ', array_unique($headers));
            return back()->withErrors([
                'file' => '
                <div style="padding: 16px; background: #fee; border-left: 4px solid #dc3545; border-radius: 6px;">
                    <div style="display: flex; align-items: center; margin-bottom: 12px;">
                        <svg style="width: 24px; height: 24px; color: #dc3545; margin-right: 10px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <strong style="font-size: 16px; color: #721c24;">Header Tidak Lengkap</strong>
                    </div>
                    <p style="margin: 0 0 10px 0; color: #721c24; font-size: 14px; line-height: 1.5;">
                        Header berikut tidak ditemukan: <strong>' . implode(', ', $missing) . '</strong>
                    </p>
                    <p style="margin: 0; color: #999; font-size: 12px;">
                        Header terdeteksi: <code style="background: #fff; padding: 2px 6px; border-radius: 3px;">' . $detectedHeaders . '</code>
                    </p>
                </div>'
            ]);
        }

        $inserted = 0;
        $duplicates = 0;
        $skipped = 0;
        $errors = [];

        // 🔁 Deteksi baris data pertama
        $dataStartRow = $headerRowIndex + 1;
        
        for ($testRow = $headerRowIndex + 1; $testRow <= $headerRowIndex + 5; $testRow++) {
            if (!isset($rows[$testRow])) continue;
            
            $testData = $rows[$testRow];
            $testText = strtolower(implode(' ', $testData));
            
            if (str_contains($testText, 'kode klasifikasi') || 
                str_contains($testText, 'uraian informasi') ||
                str_contains($testText, 'jenis arsip') ||
                str_contains($testText, 'nomor item')) {
                continue;
            }
            
            $hasContent = false;
            foreach ($testData as $cell) {
                if (!empty(trim($cell ?? ''))) {
                    $hasContent = true;
                    break;
                }
            }
            
            if (!$hasContent) {
                continue;
            }
            
            $tempHeaders = [];
            foreach ($headers as $col => $headerName) {
                $tempHeaders[$headerName] = trim($testData[$col] ?? '');
            }
            
            if (!empty($tempHeaders['kode_klasifikasi']) || !empty($tempHeaders['uraian_informasi_arsip'])) {
                $dataStartRow = $testRow;
                break;
            }
        }

        for ($i = $dataStartRow; $i <= count($rows); $i++) {
            $row = $rows[$i] ?? [];
            
            $hasData = false;
            foreach ($row as $cell) {
                if (!empty(trim($cell ?? ''))) {
                    $hasData = true;
                    break;
                }
            }
            
            if (!$hasData) {
                continue;
            }

            $data = [];
            foreach ($headers as $col => $headerName) {
                $value = $row[$col] ?? '';
                $cleanValue = trim(preg_replace('/\s+/', ' ', $value));
                
                if ($cleanValue === '-') {
                    $cleanValue = '';
                }
                
                $data[$headerName] = $cleanValue;
            }

            if (empty($data['kode_klasifikasi']) && empty($data['uraian_informasi_arsip'])) {
                $skipped++;
                $errors[] = "Baris {$i}: Data wajib kosong";
                continue;
            }

            // 🔍 Cek duplikat
            $exists = Warkah::where('kode_klasifikasi', $data['kode_klasifikasi'] ?? null)
                ->where('uraian_informasi_arsip', $data['uraian_informasi_arsip'] ?? null)
                ->first();

            if ($exists) {
                $duplicates++;
                continue;
            }

            // ✅ Simpan warkah baru - HANYA STATUS TERSEDIA
            $warkah = Warkah::create([
                'kode_klasifikasi'        => $data['kode_klasifikasi'] ?? null,
                'jenis_arsip_vital'       => $data['jenis_arsip_vital'] ?? null,
                'nomor_item_arsip'        => $data['nomor_item_arsip'] ?? null,
                'lokasi'                  => $data['lokasi'] ?? null,
                'uraian_informasi_arsip'  => $data['uraian_informasi_arsip'] ?? null,
                'kurun_waktu_berkas'      => $data['kurun_waktu_berkas'] ?? null,
                'media'                   => $data['media'] ?? null,
                'jumlah'                  => $data['jumlah'] ?? null,
                'jangka_simpan_aktif'     => $data['jangka_simpan_aktif'] ?? null,
                'jangka_simpan_inaktif'   => $data['jangka_simpan_inaktif'] ?? null,
                'tingkat_perkembangan'    => $data['tingkat_perkembangan'] ?? null,
                'ruang_penyimpanan_rak'   => $data['ruang_penyimpanan_rak'] ?? null,
                'no_boks_definitif'       => $data['no_boks_definitif'] ?? null,
                'no_folder'               => $data['no_folder'] ?? null,
                'metode_perlindungan'     => $data['metode_perlindungan'] ?? null,
                'keterangan'              => $data['keterangan'] ?? null,
                'status'                  => 'Tersedia',
                'created_by'              => auth()->id(),
            ]);

            $inserted++;
        }

        $message = "
        <div style='padding: 20px; background: #d4edda; border-left: 4px solid #28a745; border-radius: 8px;'>
            <div style='display: flex; align-items: center; margin-bottom: 12px;'>
                <svg style='width: 24px; height: 24px; color: #28a745; margin-right: 10px;' fill='currentColor' viewBox='0 0 20 20'>
                    <path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd'/>
                </svg>
                <strong style='font-size: 18px; color: #155724;'>Import Berhasil!</strong>
            </div>
            
            <div style='background: white; padding: 14px; border-radius: 6px; margin-bottom: 12px;'>
                <table style='width: 100%; font-size: 14px; color: #333;'>
                    <tr>
                        <td style='padding: 6px 0; width: 50%;'><strong>✅ Data baru ditambahkan:</strong></td>
                        <td style='padding: 6px 0; text-align: right; font-weight: 600; color: #28a745;'>{$inserted}</td>
                    </tr>
                    <tr>
                        <td style='padding: 6px 0;'><strong>⚠️ Data duplikat (dilewati):</strong></td>
                        <td style='padding: 6px 0; text-align: right; font-weight: 600; color: #ffc107;'>{$duplicates}</td>
                    </tr>
                    <tr>
                        <td style='padding: 6px 0; border-top: 1px solid #e0e0e0;'><strong>🗑️ Data kosong (dilewati):</strong></td>
                        <td style='padding: 6px 0; text-align: right; font-weight: 600; color: #6c757d; border-top: 1px solid #e0e0e0;'>{$skipped}</td>
                    </tr>
                </table>
            </div>
            
            <p style='margin: 0; font-size: 13px; color: #155724;'>
                <strong>Status:</strong> Semua data warkah baru diset sebagai <span style='background: #28a745; color: white; padding: 2px 8px; border-radius: 4px; font-weight: 600;'>Tersedia</span>
            </p>
        </div>";

        if (!empty($errors) && $inserted === 0) {
            $message .= "
                <div style='margin-top:12px; padding: 14px; background: #f8d7da; border-left: 4px solid #dc3545; border-radius: 6px;'>
                    <strong style='color: #721c24;'>⚠️ Detail Error (5 baris pertama):</strong><br>
                    <small style='color: #721c24; font-size: 12px;'>" . implode('<br>', array_slice($errors, 0, 5)) . "</small>
                </div>
            ";
        }

        return back()->with('success', $message);

    } catch (\Exception $e) {
        return back()->withErrors([
            'file' => '
            <div style="padding: 16px; background: #fee; border-left: 4px solid #dc3545; border-radius: 6px;">
                <div style="display: flex; align-items: center; margin-bottom: 12px;">
                    <svg style="width: 24px; height: 24px; color: #dc3545; margin-right: 10px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <strong style="font-size: 16px; color: #721c24;">Terjadi Kesalahan</strong>
                </div>
                <p style="margin: 0; color: #721c24; font-size: 14px; line-height: 1.5;">
                    ' . $e->getMessage() . '
                </p>
            </div>'
        ]);
    }
}
    /**
     * 🗺️ Mapping nama header yang fleksibel
     */
    private function mapHeaderName($normalizedHeader)
    {
        $mapping = [
            'kode klasifikasi' => 'kode_klasifikasi',
            'kodeklasifikasi' => 'kode_klasifikasi',
            'kode' => 'kode_klasifikasi',
            
            'uraian informasi arsip' => 'uraian_informasi_arsip',
            'uraianinformasiarsip' => 'uraian_informasi_arsip',
            'uraian informasi' => 'uraian_informasi_arsip',
            'uraian' => 'uraian_informasi_arsip',
            'informasi arsip' => 'uraian_informasi_arsip',
            
            'jenis arsip vital' => 'jenis_arsip_vital',
            'jenisarsipvital' => 'jenis_arsip_vital',
            'jenis arsip' => 'jenis_arsip_vital',
            
            'nomor item arsip' => 'nomor_item_arsip',
            'nomoritemarsip' => 'nomor_item_arsip',
            'nomor item' => 'nomor_item_arsip',
            'no item arsip' => 'nomor_item_arsip',
            
            'ruangan' => 'lokasi',
            'lokasi' => 'lokasi',
            'lok' => 'lokasi',
            
            'kurun waktu berkas' => 'kurun_waktu_berkas',
            'kurunwaktuberkas' => 'kurun_waktu_berkas',
            'kurun waktu' => 'kurun_waktu_berkas',
            'waktu berkas' => 'kurun_waktu_berkas',
            
            'media' => 'media',
            'jumlah' => 'jumlah',
            'jml' => 'jumlah',
            
            'jangka simpan aktif' => 'jangka_simpan_aktif',
            'jangkasimpanaktif' => 'jangka_simpan_aktif',
            'aktif' => 'jangka_simpan_aktif',
            'retensi aktif' => 'jangka_simpan_aktif',
            
            'jangka simpan inaktif' => 'jangka_simpan_inaktif',
            'jangkasimpaninaktif' => 'jangka_simpan_inaktif',
            'inaktif' => 'jangka_simpan_inaktif',
            'retensi inaktif' => 'jangka_simpan_inaktif',
            
            'tingkat perkembangan' => 'tingkat_perkembangan',
            'tingkatperkembangan' => 'tingkat_perkembangan',
            
            'keterangan' => 'keterangan',
            'ket' => 'keterangan',
            'status' => 'status',
            
            'metode perlindungan' => 'metode_perlindungan',
            'metodeperlindungan' => 'metode_perlindungan',
            
            'nama peminjam' => 'nama_peminjam',
            'peminjam' => 'nama_peminjam',
            'namapeminjam' => 'nama_peminjam',
            
            'nomor nota dinas' => 'nomor_nota_dinas',
            'no nota dinas' => 'nomor_nota_dinas',
            'nota dinas' => 'nomor_nota_dinas',
            'nomornotadinas' => 'nomor_nota_dinas',
            
            'file nota dinas' => 'file_nota_dinas',
            'filenotadinas' => 'file_nota_dinas',
            
            'ruang penyimpanan rak' => 'ruang_penyimpanan_rak',
            'ruang penyimpanan' => 'ruang_penyimpanan_rak',
            'ruangpenyimpanan' => 'ruang_penyimpanan_rak',
            'ruang rak' => 'ruang_penyimpanan_rak',
            'rak penyimpanan' => 'ruang_penyimpanan_rak',
            'penyimpanan rak' => 'ruang_penyimpanan_rak',
            
            'no boks definitif' => 'no_boks_definitif',
            'noboksdefinitif' => 'no_boks_definitif',
            'nomor boks definitif' => 'no_boks_definitif',
            'no boks' => 'no_boks_definitif',
            'nomor boks' => 'no_boks_definitif',
            'boks definitif' => 'no_boks_definitif',
            'boks' => 'no_boks_definitif',
            'no box' => 'no_boks_definitif',
            
            'no folder' => 'no_folder',
            'nofolder' => 'no_folder',
            'nomor folder' => 'no_folder',
            'folder' => 'no_folder',
        ];

        if (isset($mapping[$normalizedHeader])) {
            return $mapping[$normalizedHeader];
        }
        
        if (str_contains($normalizedHeader, 'ruang penyimpanan') || 
            str_contains($normalizedHeader, 'penyimpanan rak')) {
            return 'ruang_penyimpanan_rak';
        }
        
        if (str_contains($normalizedHeader, 'ruangan')) {
            return 'lokasi';
        }
        
        if (str_contains($normalizedHeader, 'lokasi')) {
            return 'lokasi';
        }
        
        foreach ($mapping as $key => $value) {
            if (str_contains($normalizedHeader, $key)) {
                return $value;
            }
        }

        return str_replace(' ', '_', $normalizedHeader);
    }
}