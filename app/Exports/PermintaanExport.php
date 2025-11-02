<?php

namespace App\Exports;

use App\Models\Permintaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PermintaanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $nama;
    protected $uraian;
    protected $tanggalPermintaan;
    protected $rowNumber = 0;

    public function __construct($nama = null, $uraian = null, $tanggalPermintaan = null)
    {
        $this->nama = $nama;
        $this->uraian = $uraian;
        $this->tanggalPermintaan = $tanggalPermintaan;
    }

    /**
     * Ambil data yang akan diexport
     */
    public function collection()
    {
        $query = Permintaan::with('warkah');

        // Filter nama pemohon
        if ($this->nama) {
            $query->where('nama_pemohon', 'like', '%' . $this->nama . '%');
        }

        // Filter uraian arsip dengan normalisasi
        if ($this->uraian) {
            $normalizedUraian = preg_replace('/\s+/', '', $this->uraian);

            $query->whereHas('warkah', function ($q) use ($normalizedUraian) {
                $q->whereRaw("REPLACE(REPLACE(uraian_informasi_arsip, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedUraian}%"])
                    ->orWhereRaw("REPLACE(REPLACE(kode_klasifikasi, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedUraian}%"])
                    ->orWhereRaw("REPLACE(REPLACE(nomor_item_arsip, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedUraian}%"]);
            });
        }

        // Filter tanggal permintaan
        if ($this->tanggalPermintaan) {
            $query->whereDate('tanggal_permintaan', $this->tanggalPermintaan);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Header kolom
     */
    public function headings(): array
    {
        return [
            'No',
            'Kode Klasifikasi Warkah',
            'Uraian Informasi Warkah',
            'Lokasi Ruangan Warkah',
            'Lokasi Penyimpanan (Rak)',
            'Nama Pemohon',
            'Instansi',
            'Nomor Identitas',
            'Alamat Lengkap',
            'Nomor Telepon',
            'Email',
            'Tanggal Permintaan',
            'Jumlah Salinan',
            'Status Permintaan',
            'Nomor Nota Dinas',
            'File Nota Dinas',
            'Catatan Tambahan'
        ];
    }

    /**
     * Mapping data ke kolom
     */
    public function map($permintaan): array
    {
        $this->rowNumber++;
        
        // Generate full URL untuk file
        $fileNotaDinas = $permintaan->nota_dinas_masuk_file 
            ? url('storage/' . $permintaan->nota_dinas_masuk_file) 
            : '-';
            
        $fileDisposisi = $permintaan->file_disposisi 
            ? url('storage/' . $permintaan->file_disposisi) 
            : '-';
        
        return [
            $this->rowNumber,
            $permintaan->warkah->kode_klasifikasi ?? '-',
            $permintaan->warkah->uraian_informasi_arsip ?? '-',
            $permintaan->warkah->lokasi ?? '-',
            $permintaan->warkah->ruang_penyimpanan_rak ?? '-',
            $permintaan->nama_pemohon,
            $permintaan->instansi ?? '-',
            $permintaan->nomor_identitas ?? '-',
            $permintaan->alamat_lengkap ?? '-',
            $permintaan->nomor_telepon ?? '-',
            $permintaan->email ?? '-',
            $permintaan->tanggal_permintaan ? \Carbon\Carbon::parse($permintaan->tanggal_permintaan)->format('Y-m-d') : '-',
            $permintaan->jumlah_salinan,
            $permintaan->status_permintaan,
            $permintaan->nota_dinas_masuk_no ?? '-',
            $fileNotaDinas,
            $permintaan->catatan_tambahan ?? '-'
        ];
    }

    /**
     * Styling untuk Excel
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:S1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB'] // Blue
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Border untuk semua data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:S{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);

        // Alignment untuk kolom tertentu
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // No
        $sheet->getStyle("L2:L{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Tanggal
        $sheet->getStyle("M2:M{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Jumlah Salinan
        $sheet->getStyle("N2:N{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Status

        // Wrap text untuk kolom panjang
        $sheet->getStyle("C2:C{$lastRow}")->getAlignment()->setWrapText(true); // Uraian Warkah
        $sheet->getStyle("D2:D{$lastRow}")->getAlignment()->setWrapText(true); // Lokasi Ruangan
        $sheet->getStyle("E2:E{$lastRow}")->getAlignment()->setWrapText(true); // Lokasi Penyimpanan
        $sheet->getStyle("I2:I{$lastRow}")->getAlignment()->setWrapText(true); // Alamat Lengkap
        $sheet->getStyle("P2:P{$lastRow}")->getAlignment()->setWrapText(true); // File Nota Dinas
        $sheet->getStyle("R2:R{$lastRow}")->getAlignment()->setWrapText(true); // File Disposisi
        $sheet->getStyle("S2:S{$lastRow}")->getAlignment()->setWrapText(true); // Catatan Tambahan

        // Set tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Color coding untuk status
        for ($row = 2; $row <= $lastRow; $row++) {
            $statusCell = $sheet->getCell("N{$row}");
            $status = $statusCell->getValue();
            
            if ($status === 'Diajukan') {
                $sheet->getStyle("N{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEF3C7'] // Yellow background
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '92400E'] // Dark yellow text
                    ]
                ]);
            } elseif ($status === 'Diterima') {
                $sheet->getStyle("N{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'DBEAFE'] // Blue background
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '1E40AF'] // Dark blue text
                    ]
                ]);
            } elseif ($status === 'Disposisi') {
                $sheet->getStyle("N{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E0E7FF'] // Indigo background
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '3730A3'] // Dark indigo text
                    ]
                ]);
            } elseif ($status === 'Disalin') {
                $sheet->getStyle("N{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E9D5FF'] // Purple background
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '6B21A8'] // Dark purple text
                    ]
                ]);
            } elseif ($status === 'Selesai') {
                $sheet->getStyle("N{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D1FAE5'] // Green background
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '065F46'] // Dark green text
                    ]
                ]);
            }
        }

        return [];
    }

    /**
     * Title sheet
     */
    public function title(): string
    {
        return 'Data Permintaan Salinan';
    }
}