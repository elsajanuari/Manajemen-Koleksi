<?php

namespace Database\Seeders;

use App\Models\Koleksi;
use App\Models\KondisiKoleksi;
use App\Models\PerawatanKoleksi;
use App\Models\User;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PerawatanKoleksiSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $pengelolas = User::where('role', 'pengelola')->get();

        if ($pengelolas->isEmpty()) {
            return;
        }

        $this->seedFromRekomendasiKondisi($faker, $pengelolas);
        $this->seedStandalonePemeliharaan($faker, $pengelolas);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, User>  $pengelolas
     */
    private function seedFromRekomendasiKondisi(Generator $faker, $pengelolas): void
    {
        $latestIds = KondisiKoleksi::query()
            ->selectRaw('MAX(id) as id')
            ->groupBy('koleksi_id')
            ->pluck('id');

        $kondisis = KondisiKoleksi::query()
            ->with('koleksi')
            ->whereIn('id', $latestIds)
            ->whereIn('rekomendasi_tindak_lanjut', array_keys(KondisiKoleksi::REKOMENDASI_TO_JENIS_PERAWATAN))
            ->inRandomOrder()
            ->limit(35)
            ->get();

        foreach ($kondisis as $index => $kondisi) {
            $jenisPerawatan = $kondisi->getJenisPerawatanDariRekomendasi();

            if (! $jenisPerawatan || ! $this->isValidJenisForKondisi($jenisPerawatan, $kondisi->kondisi)) {
                continue;
            }

            $status = match (true) {
                $index % 6 === 0 => PerawatanKoleksi::STATUS_SELESAI,
                $index % 11 === 0 => PerawatanKoleksi::STATUS_DIBATALKAN,
                default => PerawatanKoleksi::STATUS_TERJADWAL,
            };

            $minDate = Carbon::parse($kondisi->tanggal_periksa)->startOfDay();
            $jadwalTanggal = $this->resolveJadwalTanggal($faker, $minDate, $status);
            $pengelola = $pengelolas->random();

            PerawatanKoleksi::create([
                'koleksi_id' => $kondisi->koleksi_id,
                'kondisi_koleksi_id' => $kondisi->id,
                'jenis_perawatan' => $jenisPerawatan,
                'jadwal_tanggal' => $jadwalTanggal->toDateString(),
                'frekuensi' => $this->resolveFrekuensi($faker, $jenisPerawatan),
                'estimasi_durasi_menit' => $this->resolveEstimasiDurasi($jenisPerawatan, $faker),
                'penanggung_jawab' => $pengelola->name,
                'penanggung_jawab_user_id' => $pengelola->id,
                'created_by' => $pengelola->id,
                'catatan' => $this->buildCatatanFromRekomendasi($kondisi, $jenisPerawatan),
                'status' => $status,
                'tanggal_selesai' => $status === PerawatanKoleksi::STATUS_SELESAI
                    ? $jadwalTanggal->copy()->addDays($faker->numberBetween(0, 3))->toDateString()
                    : null,
                'catatan_penyelesaian' => $status === PerawatanKoleksi::STATUS_SELESAI
                    ? $this->buildCatatanPenyelesaian($jenisPerawatan)
                    : null,
                'alasan_pembatalan' => $status === PerawatanKoleksi::STATUS_DIBATALKAN
                    ? 'Dibatalkan karena penjadwalan ulang.'
                    : null,
            ]);
        }
    }

    /**
     *
     * @param  \Illuminate\Support\Collection<int, User>  $pengelolas
     */
    private function seedStandalonePemeliharaan(Generator $faker, $pengelolas): void
    {
        $scheduledKoleksiIds = PerawatanKoleksi::query()
            ->where('status', PerawatanKoleksi::STATUS_TERJADWAL)
            ->where('jenis_perawatan', 'pemeliharaan')
            ->pluck('koleksi_id');

        $koleksis = Koleksi::query()
            ->where('kondisi', 'baik')
            ->whereNotIn('id', $scheduledKoleksiIds)
            ->inRandomOrder()
            ->limit(12)
            ->get();

        foreach ($koleksis as $index => $koleksi) {
            $status = match (true) {
                $index % 5 === 0 => PerawatanKoleksi::STATUS_SELESAI,
                default => PerawatanKoleksi::STATUS_TERJADWAL,
            };

            $jadwalTanggal = $this->resolveJadwalTanggal($faker, today()->subMonths(2), $status);
            $pengelola = $pengelolas->random();

            PerawatanKoleksi::create([
                'koleksi_id' => $koleksi->id,
                'kondisi_koleksi_id' => null,
                'jenis_perawatan' => 'pemeliharaan',
                'jadwal_tanggal' => $jadwalTanggal->toDateString(),
                'frekuensi' => $faker->randomElement(['bulanan', 'triwulan', 'tahunan']),
                'estimasi_durasi_menit' => $faker->randomElement([60, 90, 120]),
                'penanggung_jawab' => $pengelola->name,
                'penanggung_jawab_user_id' => $pengelola->id,
                'created_by' => $pengelola->id,
                'catatan' => 'Jadwal pemeliharaan preventif rutin untuk menjaga kondisi koleksi tetap stabil.',
                'status' => $status,
                'tanggal_selesai' => $status === PerawatanKoleksi::STATUS_SELESAI
                    ? $jadwalTanggal->copy()->addDays($faker->numberBetween(0, 2))->toDateString()
                    : null,
                'catatan_penyelesaian' => $status === PerawatanKoleksi::STATUS_SELESAI
                    ? $this->buildCatatanPenyelesaian('pemeliharaan')
                    : null,
            ]);
        }
    }

    private function isValidJenisForKondisi(string $jenisPerawatan, string $kondisi): bool
    {
        return match ($kondisi) {
            'baik' => in_array($jenisPerawatan, ['pemeliharaan', 'pemeriksaan_ulang'], true),
            'rusak_ringan' => in_array($jenisPerawatan, ['penanganan_kerusakan', 'pemeriksaan_ulang'], true),
            'rusak_berat' => $jenisPerawatan === 'penanganan_kerusakan',
            default => false,
        };
    }

    private function resolveJadwalTanggal(Generator $faker, Carbon $minDate, string $status): Carbon
    {
        $earliest = $minDate->copy()->max(today()->subDays(60));

        if ($status === PerawatanKoleksi::STATUS_TERJADWAL) {
            return $faker->boolean(35)
                ? $earliest->copy()->addDays($faker->numberBetween(0, max(0, (int) $earliest->diffInDays(today()) - 1)))
                : today()->addDays($faker->numberBetween(1, 45));
        }

        return $earliest->copy()->addDays($faker->numberBetween(0, max(0, (int) $earliest->diffInDays(today()))));
    }

    private function resolveFrekuensi(Generator $faker, string $jenisPerawatan): string
    {
        return match ($jenisPerawatan) {
            'pemeliharaan' => $faker->randomElement(['bulanan', 'triwulan', 'tahunan']),
            'pemeriksaan_ulang' => $faker->randomElement(['sekali', 'triwulan', 'tahunan']),
            default => 'sekali',
        };
    }

    private function resolveEstimasiDurasi(string $jenisPerawatan, Generator $faker): int
    {
        return match ($jenisPerawatan) {
            'penanganan_kerusakan' => $faker->randomElement([180, 240, 360, 480]),
            'pemeriksaan_ulang' => $faker->randomElement([60, 90, 120]),
            default => $faker->randomElement([60, 90, 120]),
        };
    }

    private function buildCatatanFromRekomendasi(KondisiKoleksi $kondisi, string $jenisPerawatan): string
    {
        $baseInfo = sprintf(
            'Tindak lanjut dari pemeriksaan kondisi %s (%s). Rekomendasi: %s.',
            $kondisi->tanggal_periksa->format('d M Y'),
            $kondisi->label_kondisi,
            $kondisi->label_rekomendasi
        );

        $additionalInfo = '';
        if ($jenisPerawatan === 'penanganan_kerusakan') {
            $additionalInfo = $kondisi->koleksi->kategori === 'lukisan'
                ? ' Kerusakan pada lukisan perlu ditangani dengan teknik konservasi khusus untuk melindungi pigmen dan kanvas.'
                : ' Kerusakan pada buku perlu penanganan hati-hati untuk mempertahankan struktur dan nilai historis.';
        } elseif ($jenisPerawatan === 'pemeriksaan_ulang') {
            $additionalInfo = ' Pemeriksaan ulang diperlukan untuk memantau perkembangan kondisi dan efektivitas tindakan sebelumnya.';
        } elseif ($jenisPerawatan === 'pemeliharaan') {
            $additionalInfo = $kondisi->koleksi->kategori === 'lukisan'
                ? ' Pemeliharaan preventif mencakup pengaturan suhu, kelembapan, dan pencahayaan untuk menjaga stabilitas lukisan.'
                : ' Pemeliharaan preventif meliputi kontrol lingkungan penyimpanan untuk mencegah kerusakan lebih lanjut pada buku.';
        }

        return $baseInfo . $additionalInfo;
    }

    private function buildCatatanPenyelesaian(string $jenisPerawatan): string
    {
        return match ($jenisPerawatan) {
            'penanganan_kerusakan' => 'Penanganan kerusakan selesai sesuai protokol konservasi. Koleksi telah ditangani.',
            'pemeriksaan_ulang' => 'Pemeriksaan ulang selesai; kondisi koleksi telah dicatat dan didokumentasikan.',
            default => 'Pemeliharaan preventif selesai sesuai rencana. Lingkungan penyimpanan telah disesuaikan untuk kondisi optimal.',
        };
    }
}
