<?php

namespace App\Exports;

use App\Models\PeminjamanWarkah;
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

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $search;
    protected $status;
    protected $rowNumber = 0;

    public function __construct($search = null, $status = null)
    {
        $this->search = $search;
        $this->status = $status;
    }

    /**
     * Ambil data yang akan diexport
     */
    public function collection()
    {
        $query = PeminjamanWarkah::with('warkah');

        // Filter pencarian
        if ($this->search) {
            $search = $this->search;
            $normalizedSearch = preg_replace('/\s+/', '', $search);

            $query->where(function ($q) use ($search, $normalizedSearch) {
                $q->where('nama_peminjam', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('no_hp', 'like', '%' . $search . '%')
                    ->orWhereHas('warkah', function ($q2) use ($normalizedSearch) {
                        $q2->whereRaw("REPLACE(REPLACE(kode_klasifikasi, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedSearch}%"])
                            ->orWhereRaw("REPLACE(REPLACE(uraian_informasi_arsip, ' ', ''), '\n', '') LIKE ?", ["%{$normalizedSearch}%"]);
                    });
            });
        }

        // Filter status
        if ($this->status) {
            $query->where('status', $this->status);
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
            'Kode Klasifikasi',
            'Uraian Informasi Warkah',
            'Lokasi Ruangan Warkah',
            'Lokasi Penyimpanan (Rak)',
            'Nama Peminjam',
            'No HP',
            'Email',
            'Tanggal Pinjam',
            'Batas Peminjaman',
            'Tanggal Kembali',
            'Status',
            'Kondisi',
            'Tujuan Pinjam',
            'Nomor Nota Dinas',
            'File Nota Dinas',
            'Uraian Nota Dinas',
            'Catatan'
        ];
    }

    /**
     * Mapping data ke kolom
     */
    public function map($peminjaman): array
    {
        $this->rowNumber++;
        
        // Generate full URL untuk file nota dinas
        $fileNotaDinasUrl = $peminjaman->file_nota_dinas 
            ? url('storage/' . $peminjaman->file_nota_dinas) 
            : '-';
        
        return [
            $this->rowNumber,
            $peminjaman->warkah->kode_klasifikasi ?? '-',
            $peminjaman->warkah->uraian_informasi_arsip ?? '-',
            $peminjaman->warkah->lokasi ?? '-',
            $peminjaman->warkah->ruang_penyimpanan_rak ?? '-',
            $peminjaman->nama_peminjam,
            $peminjaman->no_hp,
            $peminjaman->email,
            $peminjaman->tanggal_pinjam->format('Y-m-d'),
            $peminjaman->batas_peminjaman->format('Y-m-d'),
            $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('Y-m-d') : '-',
            $peminjaman->status,
            $peminjaman->kondisi ?? '-',
            $peminjaman->tujuan_pinjam,
            $peminjaman->nomor_nota_dinas,
            $fileNotaDinasUrl,
            $peminjaman->uraian ?? '-',
            $peminjaman->catatan ?? '-'
        ];
    }

    /**
     * Styling untuk Excel
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:R1')->applyFromArray([
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
        $sheet->getStyle("A1:R{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);

        // Alignment untuk kolom tertentu
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("I2:K{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("L2:M{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Wrap text untuk kolom uraian dan catatan
        $sheet->getStyle("C2:C{$lastRow}")->getAlignment()->setWrapText(true);
        $sheet->getStyle("D2:D{$lastRow}")->getAlignment()->setWrapText(true); // Lokasi Ruangan
        $sheet->getStyle("E2:E{$lastRow}")->getAlignment()->setWrapText(true); // Lokasi Penyimpanan
        $sheet->getStyle("N2:N{$lastRow}")->getAlignment()->setWrapText(true);
        $sheet->getStyle("P2:P{$lastRow}")->getAlignment()->setWrapText(true); // File Nota Dinas URL
        $sheet->getStyle("Q2:Q{$lastRow}")->getAlignment()->setWrapText(true); // Uraian Nota Dinas
        $sheet->getStyle("R2:R{$lastRow}")->getAlignment()->setWrapText(true);

        // Set tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(30);

        return [];
    }

    /**
     * Title sheet
     */
    public function title(): string
    {
        return 'Data Peminjaman Warkah';
    }
}