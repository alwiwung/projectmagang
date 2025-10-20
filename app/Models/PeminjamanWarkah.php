<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PeminjamanWarkah extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_warkah';

    protected $fillable = [
        'id_warkah',
        'nama_peminjam',
        'no_hp',
        'email',
        'tanggal_pinjam',
        'tujuan_pinjam',
        'batas_peminjaman',
        'status',
        'tanggal_kembali',
         'kondisi',       // ✅ tambahkan ini
    'bukti',         // ✅ tambahkan ini
        'catatan'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'batas_peminjaman' => 'date',
        'tanggal_kembali' => 'date',
    ];

    /**
     * =============================
     * RELASI KE MASTER WARKAH
     * =============================
     */
    public function warkah()
    {
        return $this->belongsTo(Warkah::class, 'id_warkah');
    }

    /**
     * =============================
     * ACCESSOR
     * =============================
     */

    // Accessor untuk mendapatkan informasi warkah
    public function getInfoWarkahAttribute()
    {
        if ($this->warkah) {
            return $this->warkah->uraian_informasi_arsip ?? 'Tidak ada informasi';
        }
        return 'Data tidak ditemukan';
    }

    // Accessor untuk nomor/kode warkah
    public function getKodeWarkahAttribute()
    {
        if ($this->warkah) {
            // Bisa disesuaikan dengan format yang Anda inginkan
            // Misal: gabungan kode klasifikasi + nomor item
            return $this->warkah->kode_klasifikasi . '-' . $this->warkah->nomor_item_arsip;
        }
        return 'N/A';
    }

    // Accessor untuk lokasi warkah
    public function getLokasiWarkahAttribute()
    {
        if ($this->warkah) {
            return $this->warkah->ruang_penyimpanan_rak ?? '-';
        }
        return '-';
    }

    // Accessor untuk status badge color
    public function getStatusColorAttribute()
    {
        $colors = [
            'Dipinjam' => 'blue',
            'Dikembalikan' => 'green',
            'Terlambat' => 'red'
        ];

        return $colors[$this->status] ?? 'gray';
    }

    /**
     * =============================
     * METHODS
     * =============================
     */

    // Check apakah peminjaman terlambat
    public function checkTerlambat()
    {
        if ($this->status == 'Dipinjam' && Carbon::now()->gt($this->batas_peminjaman)) {
            $this->status = 'Terlambat';
            $this->save();
        }
    }

    // Hitung durasi peminjaman
    public function getDurasiPeminjamanAttribute()
    {
        if ($this->tanggal_kembali) {
            return $this->tanggal_pinjam->diffInDays($this->tanggal_kembali);
        }
        return $this->tanggal_pinjam->diffInDays(Carbon::now());
    }

    // Hitung hari keterlambatan
    public function getHariTerlambatAttribute()
    {
        if ($this->status == 'Dikembalikan') {
            return 0;
        }

        $today = Carbon::now();
        if ($today->gt($this->batas_peminjaman)) {
            return $this->batas_peminjaman->diffInDays($today);
        }

        return 0;
    }

    /**
     * =============================
     * SCOPES
     * =============================
     */

    // Scope untuk peminjaman aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'Dipinjam');
    }

    // Scope untuk peminjaman terlambat
    public function scopeTerlambat($query)
    {
        return $query->where('status', 'Terlambat');
    }

    // Scope dengan relasi warkah
    public function scopeWithWarkah($query)
    {
        return $query->with('warkah');
    }
}
