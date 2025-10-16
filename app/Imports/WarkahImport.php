<?php

namespace App\Imports;

use App\Models\Warkah;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WarkahImport implements ToCollection, WithHeadingRow
{
    public function headingRow(): int
    {
        return 8; // misal header ada di baris ke-8
    }

    public function collection(Collection $rows)
    {
        
        //     foreach ($rows as $row) {
        //     dd($row); // cek isi sebenarnya
        // }

        foreach ($rows as $row) {
            $data = array_change_key_case($row->toArray(), CASE_LOWER);

            if (empty($data['kode_klasifikasi'])) {
                continue;
            }

            Warkah::create([
                'kode_klasifikasi'        => $data['kode_klasifikasi'] ?? null,
                'jenis_arsip_vital'       => $data['jenis_arsip_vital'] ?? null,
                'nomor_item_arsip'        => $data['nomor_item_arsip'] ?? null,
                'uraian_informasi_arsip'  => $data['uraian_informasi_arsip'] ?? null,
                'kurun_waktu_berkas'      => $data['kurun_waktu_berkas'] ?? null,
                'media'                   => $data['media'] ?? null,
                'jumlah'                  => $data['jumlah'] ?? null,
                'aktif'                   => $data['aktif'] ?? null,
                'inaktif'                 => $data['inaktif'] ?? null,
                'tingkat_perkembangan'    => $data['tingkat_perkembangan'] ?? null,
                'ruang_penyimpanan_rak'   => $data['ruang_penyimpanan_rak'] ?? $data['lokasi_simpan'] ?? null,
                'no_boks_definitif'       => $data['no_boks_definitif'] ?? null,
                'no_folder'               => $data['no_folder'] ?? null,
                'metode_perlindungan'     => $data['metode_perlindungan'] ?? null,
                'keterangan'              => $data['keterangan'] ?? null,
                'status'                  => 'Tersedia',
                'created_by'              => auth()->id(),
            ]);
        }
    }
}
