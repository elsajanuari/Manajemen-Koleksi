<?php

namespace Database\Seeders;

use App\Models\Koleksi;
use App\Models\KondisiKoleksi;
use App\Models\User;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class KondisiKoleksiSeeder extends Seeder
{
    use WithoutModelEvents;

    /** @var array<string, list<string>> */
    private array $fotoKondisiByCategory = [];

    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $this->fotoKondisiByCategory = [
            'lukisan' => $this->loadKondisiFotoPaths('lukisan'),
            'buku'    => $this->loadKondisiFotoPaths('buku'),
        ];

        $pencahayaanOptions = ['rendah', 'sedang', 'tinggi'];
        $kebersihanOptions = ['baik', 'cukup', 'buruk'];
        $damageOptionsByCategory = [
            'lukisan' => [
                'Lapisan cat mulai mengelupas',
                'Kerusakan sudut bingkai',
                'Pergeseran pigmen pada lukisan',
                'Noda air dan jamur ringan pada kanvas',
                'Retakan halus pada permukaan lukisan',
                'Pucat warna dan perubahan pigmen',
                'Kerusakan pada lapisan finishing/varnish',
            ],
            'buku' => [
                'Robek halus pada tepi kertas',
                'Pinggiran buku aus karena sering dibuka',
                'Kertas menguning dan rapuh',
                'Noda air dan jamur pada halaman',
                'Warna tinta mulai memudar',
                'Jahitan buku mulai lepas',
                'Sampul buku kusam dan keriput',
            ],
        ];

        $pengelolaUsers = User::where('role', 'pengelola')->get();

        if ($pengelolaUsers->isEmpty()) {
            return;
        }

        Koleksi::chunkById(50, function ($koleksis) use ($faker, $pencahayaanOptions, $kebersihanOptions, $damageOptionsByCategory, $pengelolaUsers) {
            foreach ($koleksis as $koleksi) {
                $this->seedKondisiForKoleksi(
                    $koleksi,
                    $faker,
                    $pencahayaanOptions,
                    $kebersihanOptions,
                    $damageOptionsByCategory,
                    $pengelolaUsers
                );
            }
        });
    }

    /**
     * @param  list<string>  $pencahayaanOptions
     * @param  list<string>  $kebersihanOptions
     * @param  array<string, list<string>>  $damageOptionsByCategory
     * @param  \Illuminate\Support\Collection<int, User>  $pengelolaUsers
     */
    private function seedKondisiForKoleksi(
        Koleksi $koleksi,
        Generator $faker,
        array $pencahayaanOptions,
        array $kebersihanOptions,
        array $damageOptionsByCategory,
        $pengelolaUsers
    ): void {
        $recordCount = $faker->numberBetween(1, 2);
        $kondisiSequence = $this->buildKondisiSequence($faker, $recordCount);
        $baseDate = Carbon::now()->subDays($faker->numberBetween(7, 180));
        $originalStatusSewa = $koleksi->status_sewa;
        $previousInspection = null;

        for ($i = 0; $i < $recordCount; $i++) {
            $kondisi = $kondisiSequence[$i];
            $tanggalPeriksa = (clone $baseDate)->addDays($i * $faker->numberBetween(14, 45));

            if ($tanggalPeriksa->isFuture()) {
                $tanggalPeriksa = Carbon::now()->subDays($faker->numberBetween(1, 7));
            }

            $kebersihan = $faker->randomElement($kebersihanOptions);
            $pencahayaan = $koleksi->kategori === 'buku'
                ? $faker->randomElement(['rendah', 'sedang'])
                : $faker->randomElement($pencahayaanOptions);

            $rekomendasi = $this->resolveRekomendasi($faker, $kondisi, $kebersihan);
            
            $damageOptionsForCategory = $damageOptionsByCategory[$koleksi->kategori] ?? [];
            $jenisKerusakan = $kondisi === 'baik' ? null : ($damageOptionsForCategory !== [] ? $faker->randomElement($damageOptionsForCategory) : null);
            $catatan = $this->buildCatatan($kondisi, $kebersihan, $jenisKerusakan, $koleksi->kategori);

            $fotoOptions = $this->fotoKondisiByCategory[$koleksi->kategori] ?? [];
            $isRusak = in_array($kondisi, ['rusak_ringan', 'rusak_berat'], true);

            $fotoKondisi = $koleksi->foto
                ?: ($fotoOptions !== [] ? $faker->randomElement($fotoOptions) : null);

            $fotoKerusakan = null;
            if ($isRusak && $fotoOptions !== []) {
                $fotoKerusakan = $faker->randomElement($fotoOptions);
            }

            $previousStatusSewa = $this->resolvePreviousStatusSewa(
                $kondisi,
                $i,
                $kondisiSequence,
                $previousInspection,
                $originalStatusSewa,
                $koleksi->fresh()->status_sewa
            );

            $inspection = KondisiKoleksi::create([
                'koleksi_id' => $koleksi->id,
                'tanggal_periksa' => $tanggalPeriksa->toDateString(),
                'kondisi' => $kondisi,
                'pemeriksa' => $pengelolaUsers->random()->name,
                'catatan' => $catatan,
                'suhu' => $faker->randomFloat(1, 18, 26),
                'kelembapan' => $faker->numberBetween(40, 65),
                'pencahayaan' => $pencahayaan,
                'jenis_kerusakan' => $jenisKerusakan,
                'kebersihan_lingkungan' => $kebersihan,
                'previous_status_sewa' => $previousStatusSewa,
                'foto' => $fotoKondisi,
                'foto_kondisi_saat_ini' => $fotoKondisi,
                'foto_kerusakan' => $fotoKerusakan,
                'rekomendasi_tindak_lanjut' => $rekomendasi,
                'is_manual' => $faker->boolean(70),
            ]);

            $this->applyKoleksiSideEffects($koleksi, $inspection, $previousStatusSewa);
            $previousInspection = $inspection;
        }
    }

    /** @return list<string> */
    private function buildKondisiSequence(Generator $faker, int $count): array
    {
        if ($count === 1) {
            return [$faker->randomElement(['baik', 'rusak_ringan', 'rusak_berat'])];
        }

        return $faker->randomElement([
            ['baik', 'rusak_ringan'],
            ['baik', 'baik'],
            ['rusak_ringan', 'rusak_berat'],
            ['rusak_ringan', 'baik'],
            ['baik', 'rusak_berat'],
        ]);
    }

    private function resolveRekomendasi(Generator $faker, string $kondisi, string $kebersihan): string
    {
        return match ($kondisi) {
            'baik' => match (true) {
                $kebersihan === 'buruk' => 'pemeliharaan',
                default => $this->resolveRekomendasiBaik($faker),
            },
            'rusak_ringan' => $faker->randomElement(['penanganan_kerusakan', 'pemeriksaan_ulang']),
            'rusak_berat' => 'penanganan_kerusakan',
            default => 'tidak_perlu_tindakan',
        };
    }

    private function resolveRekomendasiBaik(Generator $faker): string
    {
        $roll = $faker->numberBetween(1, 100);

        if ($roll <= 50) {
            return 'tidak_perlu_tindakan';
        }

        if ($roll <= 75) {
            return 'pemeliharaan';
        }

        return 'pemeriksaan_ulang';
    }

    private function buildCatatan(string $kondisi, string $kebersihan, ?string $jenisKerusakan, string $kategori): string
    {
        $noteParts = [];

        if ($kondisi === 'baik') {
            if ($kategori === 'lukisan') {
                $noteParts[] = 'Kondisi fisik lukisan baik tanpa kerusakan visual.';
            } else {
                $noteParts[] = 'Kondisi fisik buku baik, tidak ada kerusakan struktur.';
            }

            if ($kebersihan === 'cukup') {
                $noteParts[] = 'Perlu peningkatan kebersihan lingkungan penyimpanan.';
            } elseif ($kebersihan === 'buruk') {
                if ($kategori === 'lukisan') {
                    $noteParts[] = 'Ditemukan debu pada permukaan; sarankan pembersihan preventif.';
                } else {
                    $noteParts[] = 'Ditemukan noda dan debu pada halaman; sarankan pembersihan hati-hati.';
                }
            }
        } elseif ($kondisi === 'rusak_ringan') {
            if ($kategori === 'lukisan') {
                $noteParts[] = 'Ditemukan kerusakan ringan pada lapisan cat atau kanvas.';
            } else {
                $noteParts[] = 'Ditemukan kerusakan ringan pada struktur atau halaman buku.';
            }

            if ($jenisKerusakan) {
                $noteParts[] = $jenisKerusakan . '.';
            }

            $noteParts[] = 'Rekomendasi: pemeriksaan ulang atau penanganan kerusakan ringan.';
        } else {
            if ($kategori === 'lukisan') {
                $noteParts[] = 'Kerusakan signifikan terdeteksi pada lukisan; perlu penanganan konservasi segera.';
            } else {
                $noteParts[] = 'Kerusakan berat pada struktur atau halaman buku; perlu tindakan konservasi segera.';
            }

            if ($jenisKerusakan) {
                $noteParts[] = $jenisKerusakan . '.';
            }

            $noteParts[] = 'Segera jadwalkan penanganan kerusakan (konservasi kuratif).';
        }

        return implode(' ', array_filter($noteParts));
    }

    /**
     * @param  list<string>  $kondisiSequence
     */
    private function resolvePreviousStatusSewa(
        string $kondisi,
        int $index,
        array $kondisiSequence,
        ?KondisiKoleksi $previousInspection,
        string $originalStatusSewa,
        string $currentStatusSewa
    ): ?string {
        if ($kondisi === 'baik' && $index > 0 && $kondisiSequence[$index - 1] !== 'baik') {
            return $previousInspection?->previous_status_sewa ?? $originalStatusSewa;
        }

        if (in_array($kondisi, ['rusak_ringan', 'rusak_berat'], true) && $currentStatusSewa !== 'tidak') {
            return $currentStatusSewa;
        }

        return null;
    }

    private function applyKoleksiSideEffects(Koleksi $koleksi, KondisiKoleksi $inspection, ?string $previousStatusSewa): void
    {
        $updates = ['kondisi' => $inspection->kondisi];

        if (in_array($inspection->kondisi, ['rusak_ringan', 'rusak_berat'], true) && $koleksi->status_sewa !== 'tidak') {
            $updates['status_sewa'] = 'tidak';
        } elseif ($inspection->kondisi === 'baik' && $previousStatusSewa && $koleksi->status_sewa === 'tidak') {
            $allowed = ['tidak', 'sewa', 'beli', 'sewa_beli'];

            if (in_array($previousStatusSewa, $allowed, true)) {
                $updates['status_sewa'] = $previousStatusSewa;
            }
        }

        $koleksi->update($updates);
    }

    /**
     *
     * @return list<string>
     */
    private function loadKondisiFotoPaths(string $category): array
    {
        $directory = storage_path('app/public/kondisi/' . $category);

        if (! File::isDirectory($directory)) {
            return [];
        }

        return collect(File::files($directory))
            ->map(fn ($file) => 'kondisi/' . $category . '/' . $file->getFilename())
            ->values()
            ->all();
    }
}
