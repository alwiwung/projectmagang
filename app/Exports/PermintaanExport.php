<?php

namespace App\Exports;

use App\Models\Permintaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PermintaanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Permintaan::select(
            'id',
            'uraian_informasi_arsip',
            'pemohon',
            'instansi',
            'tanggal_permintaan',
            'jumlah_salinan',
            'nota_dinas_masuk_no',
            'nomor_surat_disposisi',
            'nota_dinas_balasan_no',
            'status'
        )->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Uraian Informasi Arsip',
            'Pemohon',
            'Instansi',
            'Tanggal Permintaan',
            'Jumlah Salinan',
            'No. Nota Dinas Permohonan',
            'No. Surat Disposisi',
            'No. Nota Dinas Balasan',
            'Status'
        ];
    }
}
