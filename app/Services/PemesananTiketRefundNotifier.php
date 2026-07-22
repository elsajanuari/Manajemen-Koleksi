<?php

namespace App\Services;

use App\Models\PemesananTiket;
use App\Models\User;
use App\Notifications\PemesananTiketRefundNotification;
use Illuminate\Support\Facades\Log;

class PemesananTiketRefundNotifier
{
    public static function notifyRefundRequested(PemesananTiket $pemesanan): void
    {
        try {
            $pemesanan->loadMissing('ticket');

            User::query()
                ->where('role', 'pengelola')
                ->get()
                ->each(fn (User $user) => $user->notify(
                    new PemesananTiketRefundNotification($pemesanan, 'refund_requested')
                ));
        } catch (\Throwable $e) {
            Log::warning('Notifikasi pengajuan refund tiket gagal: ' . $e->getMessage());
        }
    }

    public static function notifyRefundCompleted(PemesananTiket $pemesanan): void
    {
        try {
            $pemesanan->loadMissing(['ticket', 'user']);

            $pemesanan->user?->notify(
                new PemesananTiketRefundNotification($pemesanan, 'refund_completed')
            );
        } catch (\Throwable $e) {
            Log::warning('Notifikasi selesai refund tiket gagal: ' . $e->getMessage());
        }
    }
}
