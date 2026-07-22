<?php

namespace App\Console\Commands;

use App\Models\Penyewaan;
use App\Notifications\RentalReminderNotification;
use Illuminate\Console\Command;

class SendRentalReminders extends Command
{
    protected $signature   = 'rental:send-reminders';
    protected $description = 'Kirim notifikasi pengingat pengembalian koleksi kepada penyewa';

    public function handle(): void
    {
        $today = now()->startOfDay();

        // H-3: sisa 3 hari
        $h3Date = $today->copy()->addDays(3)->toDateString();
        Penyewaan::where('status', 'aktif')
            ->whereDate('end_date', $h3Date)
            ->with(['user', 'painting'])
            ->each(function (Penyewaan $p) {
                $p->user->notify(new RentalReminderNotification($p, 'h-3'));
                $this->info("H-3 reminder sent: Penyewaan #{$p->id}");
            });

        // Hari terakhir (H-0)
        $today = $today->toDateString();
        Penyewaan::where('status', 'aktif')
            ->whereDate('end_date', $today)
            ->with(['user', 'painting'])
            ->each(function (Penyewaan $p) {
                $p->user->notify(new RentalReminderNotification($p, 'last-day'));
                $this->info("Last-day reminder sent: Penyewaan #{$p->id}");
            });

        $this->info('Selesai.');
    }
}