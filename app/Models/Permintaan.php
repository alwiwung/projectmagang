<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    protected $fillable = [
        'pemohon','instansi','tanggal_permintaan','kode_warkah','jumlah_salinan',
        'status','tahapan','barcode_path','catatan','created_by'
    ];

    protected $casts = [
        'tahapan' => 'array',
        'tanggal_permintaan' => 'date',
    ];
}
