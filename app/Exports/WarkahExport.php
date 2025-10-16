<?php

namespace App\Exports;

use App\Models\Warkah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WarkahExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Ambil semua data dari tabel master_warkah
     */
    public function collection()
    {
        return Warkah::all();
    }

    /**
     * Tentukan header kolom di file Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Kurun Waktu Berkas',
            'Lokasi',
            'Kode Klasifikasi',
            'Jenis Arsip Vital',
            'Nomor Item Arsip',
            'Uraian Informasi Arsip',
            'Media',
            'Jumlah',
            'Jangka Simpan Aktif',
            'Jangka Simpan Inaktif',
            'Tingkat Perkembangan',
            'Ruang Penyimpanan Rak',
            'No Boks Definitif',
            'No Folder',
            'Metode Perlindungan',
            'Keterangan',
            'Status',
            'Tanggal Dieksport',

        ];
    }

    /**
     * Mapping data dari model ke kolom Excel
     */
    public function map($item): array
    {
        return [
            $item->id,
            $item->kurun_waktu_berkas,
            $item->lokasi,
            $item->kode_klasifikasi,
            $item->jenis_arsip_vital,
            $item->nomor_item_arsip,
            $item->uraian_informasi_arsip,
            $item->media,
            $item->jumlah,
            $item->jangka_simpan_aktif,
            $item->jangka_simpan_inaktif,
            $item->tingkat_perkembangan,
            $item->ruang_penyimpanan_rak,
            $item->no_boks_definitif,
            $item->no_folder,
            $item->metode_perlindungan,
            $item->keterangan,
            $item->status,
            $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '',
        ];
    }
}
