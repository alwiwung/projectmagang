<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    use HasFactory;

    protected $table = 'permintaans';

    protected $fillable = [
        'id_warkah',
        'nama_pemohon',
        'instansi',
        'nomor_identitas',
        'alamat_lengkap',
        'nomor_telepon',
        'email',
        'tanggal_permintaan',
        'jumlah_salinan',
        'catatan_tambahan',
        'nota_dinas_masuk_no',
        'nota_dinas_masuk_file',
        'nomor_surat_disposisi',
        'file_disposisi',
        'status_permintaan',
    ];

    /**
     * Relasi ke tabel master_warkah
     */
    public function warkah()
    {
        return $this->belongsTo(Warkah::class, 'id_warkah');
    }

    /**
     * Accessor untuk status dengan label warna (untuk tampilan di view)
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status_permintaan) {
            'Diajukan' => '<span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">Diajukan</span>',
            'Diterima' => '<span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">Diterima</span>',
            'Disposisi' => '<span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded">Disposisi</span>',
            'Disalin' => '<span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Disalin</span>',
            'Selesai' => '<span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded">Selesai</span>',
            default => $this->status_permintaan,
        };
    }
}
