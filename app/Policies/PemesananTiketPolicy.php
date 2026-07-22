<?php

namespace App\Policies;

use App\Models\PemesananTiket;
use App\Models\User;

class PemesananTiketPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PemesananTiket $pemesananTiket): bool
    {
        return $user->id === $pemesananTiket->user_id || $user->hasRole('pengelola');
    }

    /**
     * Determine whether the user can cancel the model.
     */
    public function cancel(User $user, PemesananTiket $pemesananTiket): bool
    {
        // User harus pemilik tiket
        if ($user->id !== $pemesananTiket->user_id) {
            return false;
        }

        // Cek apakah pemesanan bisa dibatalkan
        return $pemesananTiket->dapatCancel();
    }

    /**
     * Determine whether the user can reschedule the model.
     */
    public function reschedule(User $user, PemesananTiket $pemesananTiket): bool
    {
        // User harus pemilik tiket
        if ($user->id !== $pemesananTiket->user_id) {
            return false;
        }

        // Cek apakah pemesanan bisa di-reschedule
        return $pemesananTiket->dapatReschedule();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PemesananTiket $pemesananTiket): bool
    {
        // User harus pemilik tiket dan statusnya pending
        if ($user->id !== $pemesananTiket->user_id) {
            return false;
        }

        return $pemesananTiket->isPending();
    }
}