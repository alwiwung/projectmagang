<?php

namespace App\Exports;

use App\Models\Permintaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PermintaanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Permintaan::select('id', 'uraian_informasi', 'pemohon', 'instansi', 'tanggal_permintaan', 'jumlah_salinan', 'status')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Uraian Informasi',
            'Pemohon',
            'Instansi',
            'Tanggal Permintaan',
            'Jumlah Salinan',
            'Status'
        ];
    }
}
