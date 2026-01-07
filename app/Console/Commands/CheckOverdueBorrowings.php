<?php

namespace App\Console\Commands;

use App\Models\PeminjamanWarkah;
use App\Mail\OverdueReminderMail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CheckOverdueBorrowings extends Command
{
    protected $signature = 'peminjaman:check-overdue';
    protected $description = 'Cek peminjaman yang terlambat dan kirim notifikasi';

    public function handle()
    {
        $this->info('🔍 Mengecek peminjaman yang terlambat...');
        $today = Carbon::today();

        // Ambil peminjaman yang BARU terlambat (status masih Dipinjam)
        $overdueBorrowings = PeminjamanWarkah::where('status', 'Dipinjam')
            ->whereDate('batas_peminjaman', '<', $today)
            ->get();

        if ($overdueBorrowings->isEmpty()) {
            $this->info('✅ Tidak ada peminjaman baru yang terlambat');
            return Command::SUCCESS;
        }

        $successCount = 0;
        $failCount = 0;

        foreach ($overdueBorrowings as $peminjaman) {
            try {
                // Kirim email notifikasi SEBELUM update status
                Mail::to($peminjaman->email)->send(
                    new OverdueReminderMail($peminjaman)
                );

                // Update status setelah email berhasil terkirim
                $peminjaman->update([
                    'status' => 'Terlambat',
                    'notified_at' => now() // Tambahkan kolom ini (optional)
                ]);

                $this->info("✅ Email terkirim ke: {$peminjaman->nama_lengkap} ({$peminjaman->email})");
                $successCount++;
            } catch (\Exception $e) {
                $this->error("❌ Gagal kirim ke {$peminjaman->email}: " . $e->getMessage());

                // Log error untuk monitoring
                Log::error("Overdue email failed", [
                    'peminjaman_id' => $peminjaman->id,
                    'email' => $peminjaman->email,
                    'error' => $e->getMessage()
                ]);

                $failCount++;
            }
        }

        $this->newLine();
        $this->info("📊 Ringkasan:");
        $this->info("   ✅ Berhasil: {$successCount}");
        $this->info("   ❌ Gagal: {$failCount}");
        $this->info("   📧 Total: " . ($successCount + $failCount));

        return Command::SUCCESS;
    }
}
