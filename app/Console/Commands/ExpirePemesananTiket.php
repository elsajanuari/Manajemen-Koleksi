<?php

namespace App\Console\Commands;

use App\Models\PemesananTiket;
use App\Models\TicketQuota;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpirePemesananTiket extends Command
{
    protected $signature   = 'pemesanan:expire';
    protected $description = 'Batalkan otomatis pemesanan yang belum dibayar dan tanggal kunjungannya sudah lewat';

    public function handle(): void
    {
        $today = now()->timezone('Asia/Jakarta')->startOfDay();

        $pemesanans = PemesananTiket::query()
            ->where('status', 'menunggu_pembayaran')
            ->where('tanggal_pemesanan', '<', $today)
            ->get();

        $count = 0;

        foreach ($pemesanans as $pemesanan) {
            DB::transaction(function () use ($pemesanan) {
                $pemesanan->update([
                    'status'          => 'dibatalkan',
                    'dibatalkan_pada' => now(),
                    'catatan'         => 'Dibatalkan otomatis: tidak ada pembayaran hingga tanggal kunjungan.',
                ]);
            });
            $count++;
        }

        $this->info("Selesai: {$count} pemesanan kedaluwarsa dibatalkan.");
    }
}