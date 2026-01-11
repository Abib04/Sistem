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
        // Check hosting expiry every day at 9 AM
        $schedule->command('hosting:check-expiry')
                 ->dailyAt('09:00')
                 ->timezone('Asia/Jakarta');

        // Check hosting expiry again at 5 PM
        $schedule->command('hosting:check-expiry')
                 ->dailyAt('17:00')
                 ->timezone('Asia/Jakarta');

        // Optional: Run every 6 hours during business hours
        // $schedule->command('hosting:check-expiry')
        //          ->everyHours(6)
        //          ->between('06:00', '22:00')
        //          ->timezone('Asia/Jakarta');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
