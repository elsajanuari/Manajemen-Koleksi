<?php

namespace App\Console\Commands;

use App\Models\PerawatanKoleksi;
use App\Notifications\PerawatanReminderNotification;
use Illuminate\Console\Command;

class SendPerawatanReminder extends Command
{
    protected $signature = 'perawatan:reminder';
    protected $description = 'Kirim notifikasi pengingat jadwal konservasi sehari sebelum dan hari H.';

    public function handle(): int
    {
        $perawatans = PerawatanKoleksi::with('koleksi')
            ->scheduled()
            ->where(function ($query) {
                $query->whereDate('jadwal_tanggal', today())
                      ->orWhereDate('jadwal_tanggal', today()->addDay());
            })
            ->get();

        if ($perawatans->isEmpty()) {
            $this->info('Tidak ada jadwal konservasi untuk diingatkan.');
            return self::SUCCESS;
        }

        $sentCount = 0;

        foreach ($perawatans as $perawatan) {
            $recipients = $perawatan->resolveReminderRecipients();

            foreach ($recipients as $user) {
                $exists = $user->notifications()
                    ->where('type', PerawatanReminderNotification::class)
                    ->where('data->perawatan_id', $perawatan->id)
                    ->where('data->jadwal_tanggal', $perawatan->jadwal_tanggal->toDateString())
                    ->exists();

                if ($exists) {
                    continue;
                }

                $user->notify(new PerawatanReminderNotification($perawatan));
                $sentCount++;
            }
        }

        $this->info("Notifikasi pengingat perawatan berhasil dikirim ({$sentCount} notifikasi).");

        return self::SUCCESS;
    }
}
