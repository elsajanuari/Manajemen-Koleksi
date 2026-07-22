<?php

namespace App\Console\Commands;

use App\Models\Penyewaan;
use App\Models\SerahTerima;
use App\Models\User;
use App\Notifications\PenyewaanStatusNotification;
use Illuminate\Console\Command;

class ExpireRentals extends Command
{
    protected $signature   = 'rental:expire';
    protected $description = 'Otomatis ubah status penyewaan aktif yang sudah melewati end_date menjadi pengembalian';

    public function handle(): void
    {
        $expired = Penyewaan::where('status', 'aktif')
            ->whereDate('end_date', '<', today())
            ->with(['serahTerima', 'user'])
            ->get();

        if ($expired->isEmpty()) {
            $this->info('Tidak ada penyewaan yang perlu diperbarui.');
            return;
        }

        foreach ($expired as $penyewaan) {
            $penyewaan->update(['status' => 'pengembalian']);

            // Catat di log serah terima
            $serahTerima = $penyewaan->serahTerima;
            if ($serahTerima) {
                $serahTerima->logs()->create([
                    'status'       => 'pengembalian',
                    'performed_by' => 'Sistem',
                    'message'      => 'Masa penyewaan berakhir pada ' 
                        . $penyewaan->end_date->format('d M Y') 
                        . '. Status otomatis diubah ke pengembalian.',
                ]);
            }

            // Notifikasi penyewa
            try {
                $penyewaan->user->notify(new PenyewaanStatusNotification($penyewaan));
            } catch (\Throwable $e) {
                \Log::warning('Notifikasi expire rental gagal: ' . $e->getMessage());
            }

            $this->info("Penyewaan #{$penyewaan->id} → pengembalian (end_date: {$penyewaan->end_date->format('d M Y')})");
        }

        $this->info("Selesai. {$expired->count()} penyewaan diperbarui.");
    }
}