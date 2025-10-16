<!-- <?php//

// namespace App\Imports; -->

// use App\Models\Warkah;
// use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
// use PhpOffice\PhpSpreadsheet\IOFactory;

// class WarkahImport implements ToCollection
// {
//     protected $headings = [];
//     protected $startRow = 1;

//     public function __construct($filePath = null)
//     {
//         if ($filePath) {
//             $this->detectHeaderRow($filePath);
//         }
//     }

//     /**
//      * Deteksi baris header dengan auto-merge 2-3 baris pertama
//      */
//     protected function detectHeaderRow($filePath)
//     {
//         $spreadsheet = IOFactory::load($filePath);
//         $sheet = $spreadsheet->getActiveSheet();
//         $rows = $sheet->toArray(null, true, true, true);
        
//            dd($rows[1]);
     


//         // Gabungkan maksimal 3 baris pertama (beberapa header biasanya 2 baris)
//         $mergedHeader = [];
//         for ($i = 1; $i <= 3; $i++) {
//             if (!isset($rows[$i])) continue;
//             foreach ($rows[$i] as $key => $value) {
//                 $value = trim(preg_replace('/\s+/', ' ', str_replace(["\n", "\r", "/"], ' ', strtolower($value ?? ''))));
//                 if (!isset($mergedHeader[$key])) {
//                     $mergedHeader[$key] = $value;
//                 } else {
//                     $mergedHeader[$key] = trim($mergedHeader[$key] . ' ' . $value);
//                 }
//             }
//         }

//         $this->headings = array_values($mergedHeader);
//         $this->startRow = 4; // data mulai setelah baris header
//     }

//     public function collection(Collection $rows)
//     {
//         $rows = $rows->slice($this->startRow - 1); // lewati header

//         foreach ($rows as $row) {
//             $rowArray = array_values($row->toArray());
//             if (empty(array_filter($rowArray))) continue;

//             $data = [];
//             foreach ($this->headings as $i => $key) {
//                 $normalized = str_replace(' ', '_', $key);
//                 $data[$normalized] = $rowArray[$i] ?? null;
//             }

//             // Normalisasi kolom manual (karena header di Excel bisa beda nama)
//             $warkahData = [
//                 'kode_klasifikasi'        => $this->findValue($data, ['kode_klasifikasi', 'kode_klasifikas', 'kode_klasifikasi_arsip']),
//                 'jenis_arsip_vital'       => $this->findValue($data, ['jenis_arsip_vital', 'jenis_arsip']),
//                 'nomor_item_arsip'        => $this->findValue($data, ['nomor_item_arsip', 'no_item_arsip']),
//                 'uraian_informasi_arsip'  => $this->findValue($data, ['uraian_informasi_arsip', 'uraian_arsip', 'uraian_informasi']),
//                 'kurun_waktu_berkas'      => $this->findValue($data, ['kurun_waktu_berkas', 'kurun_waktu']),
//                 'media'                   => $this->findValue($data, ['media']),
//                 'jumlah'                  => $this->findValue($data, ['jumlah']),
//                 'aktif'                   => $this->findValue($data, ['aktif']),
//                 'inaktif'                 => $this->findValue($data, ['inaktif']),
//                 'tingkat_perkembangan'    => $this->findValue($data, ['tingkat_perkembangan']),
//                 'ruang_penyimpanan_rak'   => $this->findValue($data, ['ruang_penyimpanan_rak', 'lokasi_simpan']),
//                 'no_boks_definitif'       => $this->findValue($data, ['no_boks_definitif', 'no_boks']),
//                 'no_folder'               => $this->findValue($data, ['no_folder']),
//                 'metode_perlindungan'     => $this->findValue($data, ['metode_perlindungan']),
//                 'keterangan'              => $this->findValue($data, ['keterangan']),
//                 'status'                  => 'Tersedia',
//                 'created_by'              => auth()->id(),
//             ];

//             Warkah::create($warkahData);
//         }
//     }

//     /**
//      * Temukan kolom berdasarkan beberapa kemungkinan nama
//      */
//     protected function findValue($data, $keys)
//     {
//         foreach ($keys as $key) {
//             foreach ($data as $header => $value) {
//                 if (str_contains($header, $key)) {
//                     return $value;
//                 }
//             }
//         }
//         return null;
//     }
// }
