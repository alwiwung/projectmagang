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
        $this->daysOverdue = Carbon::parse($peminjaman->batas_peminjaman)->diffInDays(Carbon::today());
    }

    public function build()
    {
        return $this->subject('⚠️ Pengingat: Keterlambatan Pengembalian Warkah')
            ->view('emails.overdue-reminder')
            ->with([
                'peminjaman' => $this->peminjaman,
                'daysOverdue' => $this->daysOverdue,
                'warkah' => $this->peminjaman->warkah
            ]);
    }
}
