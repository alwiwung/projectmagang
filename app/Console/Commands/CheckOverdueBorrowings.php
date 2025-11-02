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
    protected $description = 'Cek peminjaman yang terlambat dan kirim notifikasi email';

    public function handle()
    {
        $this->info('╔════════════════════════════════════════════════╗');
        $this->info('║  Cek Peminjaman Terlambat & Kirim Email        ║');
        $this->info('╚════════════════════════════════════════════════╝');
        $this->newLine();

        $this->info('🔍 Mengecek peminjaman yang terlambat...');
        $today = Carbon::today();

        // Query peminjaman yang BARU terlambat (belum pernah dikirim email)
        // Menggunakan scope overdue() untuk query yang lebih clean
        $overdueBorrowings = PeminjamanWarkah::overdue()
            ->with('warkah') // Eager load relasi warkah
            ->get();

        if ($overdueBorrowings->isEmpty()) {
            $this->info('✅ Tidak ada peminjaman baru yang terlambat');
            $this->info('📊 Semua peminjaman dalam batas waktu atau sudah dinotifikasi');
            return Command::SUCCESS;
        }

        $this->info("📧 Ditemukan {$overdueBorrowings->count()} peminjaman yang perlu dinotifikasi");
        $this->newLine();

        $successCount = 0;
        $failCount = 0;
        $progressBar = $this->output->createProgressBar($overdueBorrowings->count());

        foreach ($overdueBorrowings as $peminjaman) {
            try {
                // Kirim email notifikasi
                Mail::to($peminjaman->email)->send(
                    new OverdueReminderMail($peminjaman)
                );

                // Update status dan tandai sudah dikirim email
                $peminjaman->update([
                    'status' => 'Terlambat',
                    'notified_at' => now()
                ]);

                $this->newLine();
                $this->info("✅ Email terkirim ke: {$peminjaman->nama_lengkap} ({$peminjaman->email})");
                $successCount++;
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("❌ Gagal kirim ke {$peminjaman->email}");
                $this->error("   Error: " . $e->getMessage());

                // Log error untuk monitoring
                Log::error("Overdue email notification failed", [
                    'peminjaman_id' => $peminjaman->id,
                    'nama' => $peminjaman->nama_lengkap,
                    'email' => $peminjaman->email,
                    'batas_peminjaman' => $peminjaman->batas_peminjaman,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                $failCount++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Ringkasan hasil
        $this->info('╔════════════════════════════════════════════════╗');
        $this->info('║              RINGKASAN PENGIRIMAN              ║');
        $this->info('╚════════════════════════════════════════════════╝');
        $this->table(
            ['Status', 'Jumlah'],
            [
                ['✅ Berhasil', $successCount],
                ['❌ Gagal', $failCount],
                ['📧 Total Diproses', $successCount + $failCount],
            ]
        );

        // Log summary
        Log::info("Overdue check completed", [
            'success' => $successCount,
            'failed' => $failCount,
            'total' => $successCount + $failCount,
            'timestamp' => now()
        ]);

        return Command::SUCCESS;
    }
}
