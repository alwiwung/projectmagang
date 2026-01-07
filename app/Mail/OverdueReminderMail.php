<?php

namespace App\Mail;

use App\Models\PeminjamanWarkah;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class OverdueReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;
    public $daysOverdue;

    public function __construct(PeminjamanWarkah $peminjaman)
    {
        $this->peminjaman = $peminjaman;
        // Hitung hari keterlambatan
        $this->daysOverdue = Carbon::parse($peminjaman->batas_peminjaman)
            ->diffInDays(Carbon::today());
    }

    public function build()
    {
        return $this->subject('⚠️ Pengingat: Keterlambatan Pengembalian Warkah')
            ->view('emails.overdue-reminder')
            ->with([
                'nama' => $this->peminjaman->nama_lengkap,
                'kode_warkah' => $this->peminjaman->warkah->kode_warkah ?? 'N/A',
                'batas_pengembalian' => Carbon::parse($this->peminjaman->batas_peminjaman)
                    ->format('d F Y'),
                'hari_terlambat' => $this->daysOverdue,
                'tanggal_pinjam' => Carbon::parse($this->peminjaman->tanggal_pinjam)
                    ->format('d F Y'),
            ]);
    }
}
