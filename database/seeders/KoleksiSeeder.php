<?php

namespace Database\Seeders;

use App\Models\Koleksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KoleksiSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $koleksis = Koleksi::factory()->count(100)->make();

        $sequenceMap = [];

        $koleksis->sortBy('created_at')->each(function (Koleksi $koleksi) use (&$sequenceMap) {
            $year = $koleksi->created_at->format('Y');
            $key = sprintf('%s-%s', $koleksi->kategori, $year);

            if (! isset($sequenceMap[$key])) {
                $sequenceMap[$key] = Koleksi::getNextSequenceForCategory($koleksi->kategori, $year);
            }

            $koleksi->nomor_inventaris = Koleksi::generateNomorInventaris($koleksi->kategori, $year, $sequenceMap[$key]);
            $sequenceMap[$key]++;
        });

        $koleksis->sortByDesc('created_at')->each(fn (Koleksi $koleksi) => $koleksi->save());
    }
}
