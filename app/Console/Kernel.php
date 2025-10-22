<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Cek keterlambatan setiap hari jam 8 pagi
        $schedule->command('peminjaman:check-overdue')
            ->dailyAt('08:00')
            ->timezone('Asia/Jakarta');

        // Alternatif: Cek setiap jam (lebih responsif)
        // $schedule->command('peminjaman:check-overdue')->hourly();

        // Alternatif: Cek setiap 30 menit
        // $schedule->command('peminjaman:check-overdue')->everyThirtyMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
