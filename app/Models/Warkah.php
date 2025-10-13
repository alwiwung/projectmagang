<?php
// File: app/Models/Warkah.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warkah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_warkah';
    protected $fillable = [
        'no_warkah',
        'tahun',
        'no_sk',
        'nama',
        'lokasi',
        'kode_klasifikasi',
        'jenis_arsip_vital',
        'uraian_informasi_arsip',
        'jumlah',
        'tingkat_perkembangan',
        'ruang_penyimpanan_rak',
        'no_boks_definitif',
        'no_folder',
        'keterangan',
        'metode_perlindungan',
        'status',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'jumlah' => 'integer',
        'is_deleted' => 'boolean',
    ];

    // Relasi dengan User
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // Generate Nomor Warkah otomatis
    public static function generateNoWarkah()
    {
        $tahun = date('Y');
        $latestNo = self::where('tahun', $tahun)
            ->orderBy('id', 'DESC')
            ->first();

        $number = $latestNo ? (int)substr($latestNo->no_warkah, -4) + 1 : 1;
        return 'WRK-' . $tahun . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $keyword)
    {
        return $query->where('nama', 'LIKE', "%{$keyword}%")
            ->orWhere('no_sk', 'LIKE', "%{$keyword}%")
            ->orWhere('no_warkah', 'LIKE', "%{$keyword}%");
    }

    // Scope untuk filter
    public function scopeFilter($query, $filters)
    {
        if (isset($filters['tahun']) && $filters['tahun']) {
            $query->where('tahun', $filters['tahun']);
        }

        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['lokasi']) && $filters['lokasi']) {
            $query->where('lokasi', $filters['lokasi']);
        }

        if (isset($filters['kode_klasifikasi']) && $filters['kode_klasifikasi']) {
            $query->where('kode_klasifikasi', $filters['kode_klasifikasi']);
        }

        return $query;
    }
}