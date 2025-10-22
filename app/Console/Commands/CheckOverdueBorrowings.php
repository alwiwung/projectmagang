<?php

namespace App\Console\Commands;

use App\Models\PeminjamanWarkah;
use App\Notifications\OverdueNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\OverdueReminderMail;

class CheckOverdueBorrowings extends Command
{
    protected $signature = 'peminjaman:check-overdue';
    protected $description = 'Cek peminjaman yang terlambat dan kirim notifikasi';

    public function handle()
    {
        $today = Carbon::today();

        // Ambil peminjaman yang sudah melewati batas waktu dan belum dikembalikan
        $overdueBorrowings = PeminjamanWarkah::where('status', 'Dipinjam')
            ->whereDate('batas_peminjaman', '<', $today)
            ->get();

        $count = 0;

        foreach ($overdueBorrowings as $peminjaman) {
            // Update status menjadi Terlambat
            $peminjaman->update(['status' => 'Terlambat']);

            // Kirim email notifikasi
            try {
                Mail::to($peminjaman->email)->send(
                    new OverdueReminderMail($peminjaman)
                );


                $this->info("✅ Email terkirim ke: {$peminjaman->email}");
                $count++;
            } catch (\Exception $e) {
                $this->error("❌ Gagal kirim email ke {$peminjaman->email}: " . $e->getMessage());
            }
        }

        $this->info("📊 Total peminjaman terlambat: {$count}");

        return Command::SUCCESS;
    }
}
