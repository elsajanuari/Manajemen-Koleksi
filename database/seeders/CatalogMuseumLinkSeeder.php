<?php

namespace Database\Seeders;

use App\Models\Koleksi;
use App\Models\Painting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogMuseumLinkSeeder extends Seeder
{
    /**
     * Menghubungkan lukisan katalog dengan entri koleksi penjualan (untuk tombol Beli).
     */
    public function run(): void
    {
        $koleksis = Koleksi::query()->orderBy('id')->get();
        if ($koleksis->isEmpty()) {
            return;
        }

        $paintings = Painting::query()->orderBy('id')->get();

        DB::transaction(function () use ($paintings, $koleksis) {
            foreach ($paintings as $index => $painting) {
                $koleksi = $koleksis->get($index);
                if (! $koleksi) {
                    break;
                }

                $price = $koleksi->price !== null ? (int) round((float) $koleksi->price) : null;

                $painting->forceFill([
                    'koleksi_id' => $koleksi->id,
                    'sale_price' => $price,
                ])->saveQuietly();
            }
        });
    }
}
