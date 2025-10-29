<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunSchedulerDev extends Command
{
    protected $signature = 'dev:scheduler';
    protected $description = 'Run scheduler untuk development (semua platform)';

    public function handle()
    {
        $this->info('🚀 Laravel Scheduler berjalan...');
        $this->info('📌 Tekan Ctrl+C untuk berhenti');
        $this->info('⏰ Scheduler akan cek setiap menit');
        $this->newLine();

        // Jalankan schedule:work
        $this->call('schedule:work');

        return Command::SUCCESS;
    }
}
