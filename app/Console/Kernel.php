<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('perawatan:reminder')->dailyAt('07:00');
        // Jalankan pengingat rental setiap hari pada jam 08:00
        $schedule->command('rental:send-reminders')->dailyAt('08:00');

        // Warm cache kota RajaOngkir setiap hari tengah malam
        $schedule->command('rajaongkir:warm')->dailyAt('00:00');

        // Auto-expire penyewaan yang sudah melewati end_date
        $schedule->command('rental:expire')->dailyAt('00:05');

        $schedule->command('holidays:sync')->dailyAt('02:00');

        $schedule->command('pemesanan:expire')->dailyAt('00:10');
    
        if (file_exists(base_path('routes/console.php'))) {
            require base_path('routes/console.php');
        }
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
