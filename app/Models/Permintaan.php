<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permintaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'permintaans';

    protected $fillable = [
        'warkah_id',
        'pemohon',
        'instansi',
        'tanggal_permintaan',
        'uraian_informasi_arsip',
        'jumlah_salinan',
        'status',
        'tahapan',
        'barcode_path',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tahapan' => 'array',
    ];

    /**
     * Relasi ke master_warkah
     */
    public function warkah()
    {
        return $this->belongsTo(Warkah::class, 'warkah_id');
    }
}
