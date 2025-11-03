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

            // 🧩 Gabungkan dua baris header (header utama + subheader) jika ada
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

            // 🔠 Normalisasi nama header dengan mapping fleksibel
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
                    'file' => '❌ Format file tidak sesuai. Header berikut tidak ditemukan: ' . implode(', ', $missing) . 
                            '<br><small>Header terdeteksi: ' . $detectedHeaders . '</small>'
                ]);
            }

            $inserted = 0;
            $duplicates = 0;
            $skipped = 0;
            $errors = [];
            $debugInfo = []; // ✅ Untuk debugging

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
                    // Bersihkan data dari spasi, tab, newline, dan karakter whitespace lainnya
                    $value = $row[$col] ?? '';
                    $cleanValue = trim(preg_replace('/\s+/', ' ', $value));
                    
                    // ✅ PERBAIKAN: Anggap '-' sebagai data kosong
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

                // 🔍 Cek duplikat berdasarkan kode_klasifikasi dan uraian
                $exists = Warkah::where('kode_klasifikasi', $data['kode_klasifikasi'] ?? null)
                    ->where('uraian_informasi_arsip', $data['uraian_informasi_arsip'] ?? null)
                    ->first();

                if ($exists) {
                    // ✅ UPDATE: Validasi ketat untuk data peminjaman baru
                    $namaPeminjamValid = isset($data['nama_peminjam']) && 
                                        $data['nama_peminjam'] !== '' && 
                                        $data['nama_peminjam'] !== null &&
                                        $data['nama_peminjam'] !== '-' &&
                                        strlen(trim($data['nama_peminjam'])) > 0;
                    
                    $notaDinasValid = isset($data['nomor_nota_dinas']) && 
                                     $data['nomor_nota_dinas'] !== '' && 
                                     $data['nomor_nota_dinas'] !== null &&
                                     $data['nomor_nota_dinas'] !== '-' &&
                                     strlen(trim($data['nomor_nota_dinas'])) > 0;
                    
                    // ⚠️ Auto-fix: Deteksi URL di kolom nomor nota dinas
                    if ($notaDinasValid && strlen($data['nomor_nota_dinas']) > 50) {
                        if (str_starts_with($data['nomor_nota_dinas'], 'http://') || 
                            str_starts_with($data['nomor_nota_dinas'], 'https://')) {
                            $notaDinasValid = false;
                            if (empty($data['file_nota_dinas'])) {
                                $data['file_nota_dinas'] = $data['nomor_nota_dinas'];
                            }
                            $data['nomor_nota_dinas'] = '';
                        }
                    }
                    
                    $hasPeminjamanBaru = $namaPeminjamValid || $notaDinasValid;
                    
                    if ($hasPeminjamanBaru) {
                        // Update status warkah jika perlu
                        if ($exists->status !== 'Dipinjam') {
                            $exists->update(['status' => 'Dipinjam']);
                        }

                        // Cari peminjaman aktif
                        $peminjamanAktif = PeminjamanWarkah::where('id_warkah', $exists->id)
                            ->where('status', 'Dipinjam')
                            ->first();

                        if ($peminjamanAktif) {
                            // Update peminjaman yang sudah ada
                            $peminjamanAktif->update([
                                'nama_peminjam'     => $data['nama_peminjam'] ?? $peminjamanAktif->nama_peminjam,
                                'nomor_nota_dinas'  => $data['nomor_nota_dinas'] ?? $peminjamanAktif->nomor_nota_dinas,
                                'file_nota_dinas'   => $data['file_nota_dinas'] ?? $peminjamanAktif->file_nota_dinas,
                            ]);
                        } else {
                            // Buat peminjaman baru
                            PeminjamanWarkah::create([
                                'id_warkah'         => $exists->id,
                                'nama_peminjam'     => $data['nama_peminjam'] ?? null,
                                'nomor_nota_dinas'  => $data['nomor_nota_dinas'] ?? null,
                                'file_nota_dinas'   => $data['file_nota_dinas'] ?? null,
                                'tanggal_pinjam'    => now()->toDateString(),
                                'batas_peminjaman'  => now()->addDays(30)->toDateString(),
                                'status'            => 'Dipinjam',
                                'tujuan_pinjam'     => 'Import dari Excel',
                            ]);
                        }
                    }
                    
                    $duplicates++;
                    continue;
                }

                // ✅ Tentukan status berdasarkan data peminjaman yang BENAR-BENAR VALID
                // Cek dengan sangat ketat: tidak kosong DAN bukan hanya whitespace DAN bukan karakter '-'
                $namaPeminjamValid = isset($data['nama_peminjam']) && 
                                    $data['nama_peminjam'] !== '' && 
                                    $data['nama_peminjam'] !== null &&
                                    $data['nama_peminjam'] !== '-' &&
                                    strlen(trim($data['nama_peminjam'])) > 0;
                
                $notaDinasValid = isset($data['nomor_nota_dinas']) && 
                                 $data['nomor_nota_dinas'] !== '' && 
                                 $data['nomor_nota_dinas'] !== null &&
                                 $data['nomor_nota_dinas'] !== '-' &&
                                 strlen(trim($data['nomor_nota_dinas'])) > 0;
                
                // ⚠️ Peringatan: Jika nomor nota terlalu panjang (>50 karakter), kemungkinan itu URL
                if ($notaDinasValid && strlen($data['nomor_nota_dinas']) > 50) {
                    // Cek apakah ini URL
                    if (str_starts_with($data['nomor_nota_dinas'], 'http://') || 
                        str_starts_with($data['nomor_nota_dinas'], 'https://')) {
                        // Ini URL, bukan nomor nota dinas - abaikan untuk validasi peminjaman
                        $notaDinasValid = false;
                        
                        // Pindahkan ke kolom file_nota_dinas jika kolom tersebut kosong
                        if (empty($data['file_nota_dinas'])) {
                            $data['file_nota_dinas'] = $data['nomor_nota_dinas'];
                        }
                        
                        // Kosongkan nomor_nota_dinas
                        $data['nomor_nota_dinas'] = '';
                    }
                }
                
                $hasPeminjaman = $namaPeminjamValid || $notaDinasValid;
                
                // ✅ Simpan warkah baru (HANYA data warkah, TANPA data peminjaman)
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
                    'status'                  => $hasPeminjaman ? 'Dipinjam' : 'Tersedia',
                    'created_by'              => auth()->id(),
                ]);

                $inserted++;

                // 📋 Simpan data peminjaman HANYA jika kolom peminjaman benar-benar terisi
                if ($hasPeminjaman) {
                    try {
                        PeminjamanWarkah::create([
                            'id_warkah'         => $warkah->id,
                            'nama_peminjam'     => $data['nama_peminjam'] ?? null,
                            'nomor_nota_dinas'  => $data['nomor_nota_dinas'] ?? null,
                            'file_nota_dinas'   => $data['file_nota_dinas'] ?? null,
                            'tanggal_pinjam'    => now()->toDateString(),
                            'batas_peminjaman'  => now()->addDays(30)->toDateString(),
                            'status'            => 'Dipinjam',
                            'tujuan_pinjam'     => 'Import dari Excel',
                        ]);
                    } catch (\Exception $e) {
                        Log::warning("Gagal menyimpan data peminjaman untuk warkah ID {$warkah->id}: " . $e->getMessage());
                    }
                }
            }

           $message = "
    <div style='font-size:14px; line-height:1.6;'>
        <strong>✅ Import selesai!</strong><br>
        <ul style='margin:8px 0 12px 20px;'>
            <li><strong>Data baru:</strong> {$inserted}</li>
            <li><strong>Duplikat diupdate:</strong> {$duplicates}</li>
            <li><strong>Dilewati:</strong> {$skipped}</li>
        </ul>
";

if (!empty($debugInfo)) {
    $message .= "
        <div style='margin-top:12px;'>
            <strong>🔍 Debug Info (5 data pertama):</strong>
            <table border='1' cellpadding='5' cellspacing='0' style='border-collapse:collapse; font-size:12px; margin-top:8px; width:100%;'>
                <thead style='background:#f2f2f2;'>
                    <tr>
                        <th>Baris</th>
                        <th>Kode</th>
                        <th>Nama Peminjam</th>
                        <th>Pjg Nama</th>
                        <th>No Nota</th>
                        <th>Pjg Nota</th>
                        <th>Ada Peminjaman?</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
    ";

    foreach ($debugInfo as $info) {
                    $message .= "
                        <tr>
                            <td>{$info['baris']}</td>
                            <td>{$info['kode']}</td>
                            <td>" . htmlspecialchars($info['nama_peminjam']) . "</td>
                            <td>{$info['nama_peminjam_length']}</td>
                            <td>" . htmlspecialchars($info['nomor_nota']) . "</td>
                            <td>{$info['nomor_nota_length']}</td>
                            <td>" . ($info['hasPeminjaman'] === 'YA' ? '✅ Ya' : '❌ Tidak') . "</td>
                            <td><strong>{$info['status_akan_diset']}</strong></td>
                        </tr>
                    ";
                }

                $message .= "</tbody></table></div>";
            }

            if (!empty($errors) && $inserted === 0) {
                $message .= "
                    <div style='margin-top:12px; color:#b71c1c;'>
                        <strong>⚠️ Detail Error (5 baris pertama):</strong><br>
                        <small>" . implode('<br>', array_slice($errors, 0, 5)) . "</small>
                    </div>
                ";
            }

            $message .= "</div>";

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()->withErrors([
                'file' => 'Terjadi kesalahan saat membaca file: ' . $e->getMessage(),
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