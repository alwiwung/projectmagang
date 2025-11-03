<?php

namespace App\Exports;

use App\Models\Warkah;
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

class WarkahExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $rowNumber = 0;

    /**
     * Ambil semua data warkah beserta relasi peminjaman terakhir (jika ada)
     */
    public function collection()
    {
        return Warkah::with(['peminjamanTerakhir'])
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Header kolom di file Excel
     */
    public function headings(): array
    {
        return [
            'No Urut',
            'Kode Klasifikasi',
            'Jenis Arsip Vital',
            'Nomor Item Arsip',
            'Uraian Informasi Arsip',
            'Kurun Waktu Berkas',
            'Media',
            'Jumlah',
            'Jangka Simpan Aktif',
            'Jangka Simpan Inaktif',
            'Tingkat Perkembangan',
            'Ruangan',
            'Ruang Penyimpanan Rak',
            'No Boks Definitif',
            'No Folder',
            'Metode Perlindungan',
            'Keterangan',
            'Status',
            'Nama Peminjam',
            'Nomor Nota Dinas',
            'File Nota Dinas',
        ];
    }

    /**
     * Mapping data ke baris Excel
     */
    public function map($item): array
    {
        $this->rowNumber++;
        
        $peminjaman = $item->peminjamanTerakhir;

        // 🔸 Tampilkan data peminjaman hanya jika status = Dipinjam atau Terlambat
        $showBorrowerData = in_array($item->status, ['Dipinjam', 'Terlambat']);

        $namaPeminjam = $showBorrowerData && $peminjaman
            ? ($peminjaman->nama_peminjam ?? '-')
            : '-';

        $nomorNotaDinas = $showBorrowerData && $peminjaman
            ? ($peminjaman->nomor_nota_dinas ?? '-')
            : '-';

        // Fix: Cek apakah file sudah full URL atau masih path relatif
        $fileNotaDinas = '-';
        if ($showBorrowerData && $peminjaman && $peminjaman->file_nota_dinas) {
            $filePath = $peminjaman->file_nota_dinas;
            
            // Jika sudah full URL (http/https), gunakan langsung
            if (str_starts_with($filePath, 'http://') || str_starts_with($filePath, 'https://')) {
                $fileNotaDinas = $filePath;
            } else {
                // Jika masih path storage, convert ke URL
                $fileNotaDinas = url('storage/' . ltrim($filePath, '/'));
            }
        }

        return [
            $this->rowNumber,
            $item->kode_klasifikasi ?? '-',
            $item->jenis_arsip_vital ?? '-',
            $item->nomor_item_arsip ?? '-',
            $item->uraian_informasi_arsip ?? '-',
            $item->kurun_waktu_berkas ?? '-',
            $item->media ?? '-',
            $item->jumlah ?? '-',
            $item->jangka_simpan_aktif ?? '-',
            $item->jangka_simpan_inaktif ?? '-',
            $item->tingkat_perkembangan ?? '-',
            $item->lokasi ?? '-',
            $item->ruang_penyimpanan_rak ?? '-',
            $item->no_boks_definitif ?? '-',
            $item->no_folder ?? '-',
            $item->metode_perlindungan ?? '-',
            $item->keterangan ?? '-',
            $item->status ?? 'Tersedia',
            $namaPeminjam,
            $nomorNotaDinas,
            $fileNotaDinas,
        ];
    }

    /**
     * Styling untuk Excel
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:U1')->applyFromArray([
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
        $sheet->getStyle("A1:U{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);

        // Alignment untuk kolom tertentu
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // No Urut
        $sheet->getStyle("H2:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Jumlah
        $sheet->getStyle("I2:J{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Jangka Simpan
        $sheet->getStyle("R2:R{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Status

        // Wrap text untuk kolom panjang
        $sheet->getStyle("E2:E{$lastRow}")->getAlignment()->setWrapText(true); // Uraian Informasi Arsip
        $sheet->getStyle("L2:L{$lastRow}")->getAlignment()->setWrapText(true); // Ruangan
        $sheet->getStyle("M2:M{$lastRow}")->getAlignment()->setWrapText(true); // Ruang Penyimpanan Rak
        $sheet->getStyle("P2:P{$lastRow}")->getAlignment()->setWrapText(true); // Metode Perlindungan
        $sheet->getStyle("Q2:Q{$lastRow}")->getAlignment()->setWrapText(true); // Keterangan
        $sheet->getStyle("U2:U{$lastRow}")->getAlignment()->setWrapText(true); // File Nota Dinas

        // Set tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Color coding untuk status
        for ($row = 2; $row <= $lastRow; $row++) {
            $statusCell = $sheet->getCell("R{$row}");
            $status = $statusCell->getValue();
            
            if ($status === 'Dipinjam') {
                $sheet->getStyle("R{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEF3C7'] // Yellow background
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '92400E'] // Dark yellow text
                    ]
                ]);
            } elseif ($status === 'Terlambat') {
                $sheet->getStyle("R{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEE2E2'] // Red background
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '991B1B'] // Dark red text
                    ]
                ]);
            } elseif ($status === 'Tersedia') {
                $sheet->getStyle("R{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D1FAE5'] // Green background
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '065F46'] // Dark green text
                    ]
                ]);
            } elseif ($status === 'Rusak') {
                $sheet->getStyle("R{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FED7AA'] // Orange background
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '9A3412'] // Dark orange text
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
        return 'Data Master Warkah';
    }
}