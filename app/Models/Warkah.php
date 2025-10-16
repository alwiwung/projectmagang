<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Warkah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_warkah';

    protected $fillable = [
        'kurun_waktu_berkas',
        'lokasi',
        'kode_klasifikasi',
        'jenis_arsip_vital',
        'nomor_item_arsip',
        'uraian_informasi_arsip',
        'media',
        'jumlah',
        'jangka_simpan_aktif',
        'jangka_simpan_inaktif',
        'tingkat_perkembangan',
        'ruang_penyimpanan_rak',
        'no_boks_definitif',
        'no_folder',
        'metode_perlindungan',
        'keterangan',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'is_deleted',
    ];

    /**
     * =============================
     * Relasi ke tabel users
     * =============================
     */
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

    /**
     * =============================
     * Scope pencarian bebas
     * =============================
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('ruang_penyimpanan_rak', 'LIKE', "%{$keyword}%")
              ->orWhere('kode_klasifikasi', 'LIKE', "%{$keyword}%")
              ->orWhere('uraian_informasi_arsip', 'LIKE', "%{$keyword}%")
              ->orWhere('kurun_waktu_berkas', 'LIKE', "%{$keyword}%");
        });
    }

    /**
     * =============================
     * Scope filter berdasarkan kolom
     * =============================
     */
    public function scopeFilter($query, $filters)
    {
        if (!empty($filters['kurun_waktu_berkas'])) {
            $query->where('kurun_waktu_berkas', $filters['kurun_waktu_berkas']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['ruang_penyimpanan_rak'])) {
            $query->where('ruang_penyimpanan_rak', $filters['ruang_penyimpanan_rak']);
        }

        if (!empty($filters['kode_klasifikasi'])) {
            $query->where('kode_klasifikasi', $filters['kode_klasifikasi']);
        }

        return $query;
    }
}
