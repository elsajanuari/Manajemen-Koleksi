<?php

namespace App\Console\Commands;

use App\Services\RajaOngkirService;
use Illuminate\Console\Command;

class WarmRajaOngkirCache extends Command
{
    protected $signature = 'rajaongkir:warm-cache';
    protected $description = 'Pre-warm cache kota RajaOngkir untuk semua provinsi';

    public function handle(RajaOngkirService $service): void
    {
        $this->info('Pre-warming RajaOngkir cache...');
        foreach (range(1, 34) as $id) {
            $cities = $service->getCitiesByProvince($id);
            $this->line("  Provinsi {$id}: " . count($cities) . " kota");
        }
        $this->info('Selesai.');
    }
}