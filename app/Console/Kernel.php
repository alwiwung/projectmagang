<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // PRODUCTION: Cek setiap hari jam 8 pagi
        $schedule->command('peminjaman:check-overdue')
            ->dailyAt('08:00')
            ->timezone('Asia/Jakarta')
            ->appendOutputTo(storage_path('logs/overdue-check.log'));

        // DEVELOPMENT (uncomment untuk testing): Cek setiap menit
        // $schedule->command('peminjaman:check-overdue')
        //     ->everyMinute()
        //     ->appendOutputTo(storage_path('logs/overdue-check.log'));
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
