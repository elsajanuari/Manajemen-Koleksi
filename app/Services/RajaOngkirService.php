<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected int $originCityId;

    public function __construct()
    {
        $this->apiKey       = config('services.rajaongkir.api_key');
        $this->baseUrl      = config('services.rajaongkir.base_url', 'https://api.rajaongkir.com/starter');
        $this->originCityId = (int) config('services.rajaongkir.origin_city_id', 429);
    }

    // ─────────────────────────────────────────────────────────────
    // Ambil semua provinsi (cache 24 jam, fallback ke data statis)
    // ─────────────────────────────────────────────────────────────
    public function getProvinces(): array
    {
        return Cache::remember('rajaongkir_provinces', 86400, function () {
            try {
                $response = Http::timeout(8)->withHeaders(['key' => $this->apiKey])
                    ->get("{$this->baseUrl}/province");

                if ($response->successful()) {
                    $data = $response->json('rajaongkir.results', []);
                    if (! empty($data)) {
                        return $data;
                    }
                }
            } catch (\Exception $e) {
                Log::warning('RajaOngkir getProvinces gagal, pakai data lokal', ['error' => $e->getMessage()]);
            }

            return $this->getStaticProvinces();
        });
    }

    // ─────────────────────────────────────────────────────────────
    // Ambil kota berdasarkan province_id (cache 24 jam, fallback)
    // ─────────────────────────────────────────────────────────────
    public function getCitiesByProvince(int $provinceId): array
    {
        // Cek cache dulu (sudah diisi WarmRajaOngkirCache atau sebelumnya)
        $cacheKey = "rajaongkir_cities_{$provinceId}";

        return Cache::remember($cacheKey, 86400 * 30, function () use ($provinceId) {
            // 1. Coba ambil dari static data dulu — INSTAN, tidak perlu HTTP
            $static = $this->getStaticCitiesByProvince($provinceId);
            if (! empty($static)) {
                return $static;
            }

            // 2. Fallback ke API hanya kalau static kosong (tidak akan terjadi)
            try {
                $response = Http::timeout(5)->withHeaders(['key' => $this->apiKey])
                    ->get("{$this->baseUrl}/city", ['province' => $provinceId]);

                if ($response->successful()) {
                    $data = $response->json('rajaongkir.results', []);
                    if (! empty($data)) {
                        return $data;
                    }
                }
            } catch (\Exception $e) {
                Log::warning('RajaOngkir getCities gagal', [
                    'province_id' => $provinceId,
                    'error'       => $e->getMessage(),
                ]);
            }

            return [];
        });
    }

    // ─────────────────────────────────────────────────────────────
    // Hitung ongkir ke kota tujuan
    // ─────────────────────────────────────────────────────────────
    public function calculateCost(int $destinationCityId, int $weightGram, string $courier = 'jne'): array
    {
        if ($destinationCityId === $this->originCityId) {
            return [];
        }

        try {
            $response = Http::timeout(10)->withHeaders(['key' => $this->apiKey])
                ->post("{$this->baseUrl}/cost", [
                    'origin'      => $this->originCityId,
                    'destination' => $destinationCityId,
                    'weight'      => max(1, $weightGram),
                    'courier'     => $courier,
                ]);

            if ($response->successful()) {
                $results = $response->json('rajaongkir.results', []);
                if (! empty($results)) {
                    return $this->flattenCourierResults($results, $courier);
                }
            }
        } catch (\Exception $e) {
            Log::warning('RajaOngkir calculateCost gagal', [
                'destination' => $destinationCityId,
                'error'       => $e->getMessage(),
            ]);
        }

        // Fallback: estimasi biaya berdasarkan zona
        return $this->getStaticCostEstimate($destinationCityId, $weightGram, $courier);
    }

    // ─────────────────────────────────────────────────────────────
    // Hitung ongkir semua kurir sekaligus
    // ─────────────────────────────────────────────────────────────
    public function calculateAllCouriers(int $destinationCityId, int $weightGram): array
    {
        if ($destinationCityId === $this->originCityId) {
            return [];
        }

        $couriers    = ['jne', 'tiki', 'pos'];
        $allServices = [];

        foreach ($couriers as $courier) {
            $services    = $this->calculateCost($destinationCityId, $weightGram, $courier);
            $allServices = array_merge($allServices, $services);
        }

        usort($allServices, fn ($a, $b) => $a['cost'] <=> $b['cost']);

        return $allServices;
    }

    // ─────────────────────────────────────────────────────────────
    // Cari city_id berdasarkan nama kota
    // ─────────────────────────────────────────────────────────────
    public function findCityId(string $cityName, int $provinceId): ?int
    {
        $cities       = $this->getCitiesByProvince($provinceId);
        $cityNameLower = strtolower(trim($cityName));

        foreach ($cities as $city) {
            $name = strtolower($city['city_name'] ?? '');
            $full = strtolower(($city['type'] ?? '') . ' ' . ($city['city_name'] ?? ''));

            if ($name === $cityNameLower || $full === $cityNameLower || str_contains($cityNameLower, $name)) {
                return (int) $city['city_id'];
            }
        }

        return null;
    }

    public function getOriginCityId(): int
    {
        return $this->originCityId;
    }

    // ═════════════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ═════════════════════════════════════════════════════════════

    private function flattenCourierResults(array $results, string $courier): array
    {
        $services = [];
        foreach ($results as $courierResult) {
            $courierName = $courierResult['name'] ?? $courier;
            $courierCode = $courierResult['code'] ?? $courier;
            foreach ($courierResult['costs'] ?? [] as $cost) {
                $services[] = [
                    'courier_code' => $courierCode,
                    'courier_name' => $courierName,
                    'service'      => $cost['service'] ?? '',
                    'description'  => $cost['description'] ?? '',
                    'cost'         => $cost['cost'][0]['value'] ?? 0,
                    'etd'          => $cost['cost'][0]['etd'] ?? '-',
                    'label'        => $courierName . ' ' . ($cost['service'] ?? '')
                                    . ' — Rp ' . number_format($cost['cost'][0]['value'] ?? 0, 0, ',', '.')
                                    . ' (ETD: ' . ($cost['cost'][0]['etd'] ?? '-') . ' hari)',
                ];
            }
        }
        return $services;
    }

    // ─────────────────────────────────────────────────────────────
    // Estimasi ongkir statis berdasarkan zone kota tujuan
    // Hanya dipakai jika API RajaOngkir tidak bisa diakses
    // ─────────────────────────────────────────────────────────────
    private function getStaticCostEstimate(int $destinationCityId, int $weightGram, string $courier): array
    {
        // Tentukan zona berdasarkan province_id kota tujuan
        $cities     = $this->getAllStaticCities();
        $targetCity = collect($cities)->firstWhere('city_id', (string) $destinationCityId);

        if (! $targetCity) {
            return [];
        }

        $provinceId = (int) ($targetCity['province_id'] ?? 0);

        // Zona 1: Jawa Barat (province_id=9) → murah
        // Zona 2: Jawa (province_id=3,5,6,10,11) → menengah
        // Zona 3: luar Jawa → mahal
        $weightKg = max(1, ceil($weightGram / 1000));

        [$rateJne, $rateTiki, $ratePos] = match (true) {
            $provinceId === 9                          => [9000, 8000, 7000],   // Jawa Barat
            in_array($provinceId, [3, 5, 6, 10, 11])  => [12000, 11000, 10000], // Jawa lainnya
            in_array($provinceId, [1, 21, 22])         => [20000, 19000, 18000], // Bali & NTB/NTT
            default                                    => [28000, 27000, 25000], // Luar Jawa
        };

        $courierMap = [
            'jne'  => ['name' => 'JNE',  'rate' => $rateJne,  'etd' => '2-3'],
            'tiki' => ['name' => 'TIKI', 'rate' => $rateTiki, 'etd' => '2-4'],
            'pos'  => ['name' => 'POS',  'rate' => $ratePos,  'etd' => '3-5'],
        ];

        if (! isset($courierMap[$courier])) {
            return [];
        }

        $c    = $courierMap[$courier];
        $cost = $c['rate'] * $weightKg;

        return [[
            'courier_code' => $courier,
            'courier_name' => $c['name'],
            'service'      => 'REG',
            'description'  => 'Layanan Reguler (Estimasi)',
            'cost'         => $cost,
            'etd'          => $c['etd'],
            'label'        => $c['name'] . ' REG — Rp ' . number_format($cost, 0, ',', '.')
                            . ' (ETD: ' . $c['etd'] . ' hari)',
        ]];
    }

    // ─────────────────────────────────────────────────────────────
    // Data statis provinsi Indonesia
    // ─────────────────────────────────────────────────────────────
    private function getStaticProvinces(): array
    {
        return [
            ['province_id' => '34', 'province' => 'Aceh'],
            ['province_id' => '1',  'province' => 'Bali'],
            ['province_id' => '2',  'province' => 'Bangka Belitung'],
            ['province_id' => '3',  'province' => 'Banten'],
            ['province_id' => '4',  'province' => 'Bengkulu'],
            ['province_id' => '5',  'province' => 'DI Yogyakarta'],
            ['province_id' => '6',  'province' => 'DKI Jakarta'],
            ['province_id' => '7',  'province' => 'Gorontalo'],
            ['province_id' => '8',  'province' => 'Jambi'],
            ['province_id' => '9',  'province' => 'Jawa Barat'],
            ['province_id' => '10', 'province' => 'Jawa Tengah'],
            ['province_id' => '11', 'province' => 'Jawa Timur'],
            ['province_id' => '12', 'province' => 'Kalimantan Barat'],
            ['province_id' => '13', 'province' => 'Kalimantan Selatan'],
            ['province_id' => '14', 'province' => 'Kalimantan Tengah'],
            ['province_id' => '15', 'province' => 'Kalimantan Timur'],
            ['province_id' => '16', 'province' => 'Kalimantan Utara'],
            ['province_id' => '17', 'province' => 'Kepulauan Riau'],
            ['province_id' => '18', 'province' => 'Lampung'],
            ['province_id' => '19', 'province' => 'Maluku'],
            ['province_id' => '20', 'province' => 'Maluku Utara'],
            ['province_id' => '21', 'province' => 'Nusa Tenggara Barat'],
            ['province_id' => '22', 'province' => 'Nusa Tenggara Timur'],
            ['province_id' => '23', 'province' => 'Papua'],
            ['province_id' => '24', 'province' => 'Papua Barat'],
            ['province_id' => '25', 'province' => 'Riau'],
            ['province_id' => '26', 'province' => 'Sulawesi Barat'],
            ['province_id' => '27', 'province' => 'Sulawesi Selatan'],
            ['province_id' => '28', 'province' => 'Sulawesi Tengah'],
            ['province_id' => '29', 'province' => 'Sulawesi Tenggara'],
            ['province_id' => '30', 'province' => 'Sulawesi Utara'],
            ['province_id' => '31', 'province' => 'Sumatera Barat'],
            ['province_id' => '32', 'province' => 'Sumatera Selatan'],
            ['province_id' => '33', 'province' => 'Sumatera Utara'],
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // Data statis kota berdasarkan province_id
    // ─────────────────────────────────────────────────────────────
    private function getStaticCitiesByProvince(int $provinceId): array
    {
        // Tidak perlu Cache::remember lagi di sini
        // karena pemanggil (getCitiesByProvince) sudah cache hasilnya
        return array_values(array_filter(
            $this->getAllStaticCities(),
            fn ($c) => (int) $c['province_id'] === $provinceId
        ));
    }

    private function getAllStaticCities(): array
    {
        // Data lengkap 510 kota/kabupaten Indonesia
        // province_id mengikuti ID RajaOngkir
        return [
            // ── Bali (1) ──────────────────────────────────────────
            ['city_id'=>'1','province_id'=>'1','type'=>'Kabupaten','city_name'=>'Badung','postal_code'=>'80351'],
            ['city_id'=>'2','province_id'=>'1','type'=>'Kabupaten','city_name'=>'Bangli','postal_code'=>'80619'],
            ['city_id'=>'3','province_id'=>'1','type'=>'Kabupaten','city_name'=>'Buleleng','postal_code'=>'81119'],
            ['city_id'=>'4','province_id'=>'1','type'=>'Kota','city_name'=>'Denpasar','postal_code'=>'80227'],
            ['city_id'=>'5','province_id'=>'1','type'=>'Kabupaten','city_name'=>'Gianyar','postal_code'=>'80511'],
            ['city_id'=>'6','province_id'=>'1','type'=>'Kabupaten','city_name'=>'Jembrana','postal_code'=>'82218'],
            ['city_id'=>'7','province_id'=>'1','type'=>'Kabupaten','city_name'=>'Karangasem','postal_code'=>'80811'],
            ['city_id'=>'8','province_id'=>'1','type'=>'Kabupaten','city_name'=>'Klungkung','postal_code'=>'80711'],
            ['city_id'=>'9','province_id'=>'1','type'=>'Kabupaten','city_name'=>'Tabanan','postal_code'=>'82119'],
            // ── Bangka Belitung (2) ───────────────────────────────
            ['city_id'=>'10','province_id'=>'2','type'=>'Kabupaten','city_name'=>'Bangka','postal_code'=>'33211'],
            ['city_id'=>'11','province_id'=>'2','type'=>'Kabupaten','city_name'=>'Bangka Barat','postal_code'=>'33315'],
            ['city_id'=>'12','province_id'=>'2','type'=>'Kabupaten','city_name'=>'Bangka Selatan','postal_code'=>'33719'],
            ['city_id'=>'13','province_id'=>'2','type'=>'Kabupaten','city_name'=>'Bangka Tengah','postal_code'=>'33613'],
            ['city_id'=>'14','province_id'=>'2','type'=>'Kabupaten','city_name'=>'Belitung','postal_code'=>'33419'],
            ['city_id'=>'15','province_id'=>'2','type'=>'Kabupaten','city_name'=>'Belitung Timur','postal_code'=>'33519'],
            ['city_id'=>'16','province_id'=>'2','type'=>'Kota','city_name'=>'Pangkal Pinang','postal_code'=>'33121'],
            // ── Banten (3) ────────────────────────────────────────
            ['city_id'=>'17','province_id'=>'3','type'=>'Kota','city_name'=>'Cilegon','postal_code'=>'42417'],
            ['city_id'=>'18','province_id'=>'3','type'=>'Kabupaten','city_name'=>'Lebak','postal_code'=>'42319'],
            ['city_id'=>'19','province_id'=>'3','type'=>'Kabupaten','city_name'=>'Pandeglang','postal_code'=>'42219'],
            ['city_id'=>'20','province_id'=>'3','type'=>'Kabupaten','city_name'=>'Serang','postal_code'=>'42119'],
            ['city_id'=>'21','province_id'=>'3','type'=>'Kota','city_name'=>'Serang','postal_code'=>'42111'],
            ['city_id'=>'22','province_id'=>'3','type'=>'Kota','city_name'=>'Tangerang','postal_code'=>'15111'],
            ['city_id'=>'23','province_id'=>'3','type'=>'Kabupaten','city_name'=>'Tangerang','postal_code'=>'15911'],
            ['city_id'=>'24','province_id'=>'3','type'=>'Kota','city_name'=>'Tangerang Selatan','postal_code'=>'15311'],
            // ── Bengkulu (4) ──────────────────────────────────────
            ['city_id'=>'25','province_id'=>'4','type'=>'Kabupaten','city_name'=>'Bengkulu Selatan','postal_code'=>'38519'],
            ['city_id'=>'26','province_id'=>'4','type'=>'Kabupaten','city_name'=>'Bengkulu Tengah','postal_code'=>'38119'],
            ['city_id'=>'27','province_id'=>'4','type'=>'Kabupaten','city_name'=>'Bengkulu Utara','postal_code'=>'38619'],
            ['city_id'=>'28','province_id'=>'4','type'=>'Kota','city_name'=>'Bengkulu','postal_code'=>'38227'],
            ['city_id'=>'29','province_id'=>'4','type'=>'Kabupaten','city_name'=>'Kaur','postal_code'=>'38919'],
            ['city_id'=>'30','province_id'=>'4','type'=>'Kabupaten','city_name'=>'Kepahiang','postal_code'=>'39319'],
            ['city_id'=>'31','province_id'=>'4','type'=>'Kabupaten','city_name'=>'Lebong','postal_code'=>'39219'],
            ['city_id'=>'32','province_id'=>'4','type'=>'Kabupaten','city_name'=>'Muko Muko','postal_code'=>'38719'],
            ['city_id'=>'33','province_id'=>'4','type'=>'Kabupaten','city_name'=>'Rejang Lebong','postal_code'=>'39119'],
            ['city_id'=>'34','province_id'=>'4','type'=>'Kabupaten','city_name'=>'Seluma','postal_code'=>'38819'],
            // ── DI Yogyakarta (5) ─────────────────────────────────
            ['city_id'=>'35','province_id'=>'5','type'=>'Kabupaten','city_name'=>'Bantul','postal_code'=>'55719'],
            ['city_id'=>'36','province_id'=>'5','type'=>'Kabupaten','city_name'=>'Gunung Kidul','postal_code'=>'55819'],
            ['city_id'=>'37','province_id'=>'5','type'=>'Kabupaten','city_name'=>'Kulon Progo','postal_code'=>'55619'],
            ['city_id'=>'38','province_id'=>'5','type'=>'Kabupaten','city_name'=>'Sleman','postal_code'=>'55519'],
            ['city_id'=>'39','province_id'=>'5','type'=>'Kota','city_name'=>'Yogyakarta','postal_code'=>'55111'],
            // ── DKI Jakarta (6) ───────────────────────────────────
            ['city_id'=>'40','province_id'=>'6','type'=>'Kota','city_name'=>'Jakarta Barat','postal_code'=>'11220'],
            ['city_id'=>'41','province_id'=>'6','type'=>'Kota','city_name'=>'Jakarta Pusat','postal_code'=>'10540'],
            ['city_id'=>'42','province_id'=>'6','type'=>'Kota','city_name'=>'Jakarta Selatan','postal_code'=>'12230'],
            ['city_id'=>'43','province_id'=>'6','type'=>'Kota','city_name'=>'Jakarta Timur','postal_code'=>'13330'],
            ['city_id'=>'44','province_id'=>'6','type'=>'Kota','city_name'=>'Jakarta Utara','postal_code'=>'14140'],
            ['city_id'=>'45','province_id'=>'6','type'=>'Kabupaten','city_name'=>'Kepulauan Seribu','postal_code'=>'14550'],
            // ── Gorontalo (7) ─────────────────────────────────────
            ['city_id'=>'46','province_id'=>'7','type'=>'Kabupaten','city_name'=>'Boalemo','postal_code'=>'96319'],
            ['city_id'=>'47','province_id'=>'7','type'=>'Kabupaten','city_name'=>'Bone Bolango','postal_code'=>'96511'],
            ['city_id'=>'48','province_id'=>'7','type'=>'Kabupaten','city_name'=>'Gorontalo','postal_code'=>'96419'],
            ['city_id'=>'49','province_id'=>'7','type'=>'Kota','city_name'=>'Gorontalo','postal_code'=>'96115'],
            ['city_id'=>'50','province_id'=>'7','type'=>'Kabupaten','city_name'=>'Gorontalo Utara','postal_code'=>'96611'],
            ['city_id'=>'51','province_id'=>'7','type'=>'Kabupaten','city_name'=>'Pohuwato','postal_code'=>'96419'],
            // ── Jambi (8) ─────────────────────────────────────────
            ['city_id'=>'52','province_id'=>'8','type'=>'Kabupaten','city_name'=>'Batanghari','postal_code'=>'36619'],
            ['city_id'=>'53','province_id'=>'8','type'=>'Kota','city_name'=>'Jambi','postal_code'=>'36111'],
            ['city_id'=>'54','province_id'=>'8','type'=>'Kabupaten','city_name'=>'Kerinci','postal_code'=>'37119'],
            ['city_id'=>'55','province_id'=>'8','type'=>'Kabupaten','city_name'=>'Merangin','postal_code'=>'37311'],
            ['city_id'=>'56','province_id'=>'8','type'=>'Kabupaten','city_name'=>'Muaro Jambi','postal_code'=>'36311'],
            ['city_id'=>'57','province_id'=>'8','type'=>'Kabupaten','city_name'=>'Sarolangun','postal_code'=>'37419'],
            ['city_id'=>'58','province_id'=>'8','type'=>'Kota','city_name'=>'Sungai Penuh','postal_code'=>'37100'],
            ['city_id'=>'59','province_id'=>'8','type'=>'Kabupaten','city_name'=>'Tanjung Jabung Barat','postal_code'=>'36519'],
            ['city_id'=>'60','province_id'=>'8','type'=>'Kabupaten','city_name'=>'Tanjung Jabung Timur','postal_code'=>'36719'],
            ['city_id'=>'61','province_id'=>'8','type'=>'Kabupaten','city_name'=>'Tebo','postal_code'=>'37519'],
            // ── Jawa Barat (9) ────────────────────────────────────
            ['city_id'=>'62','province_id'=>'9','type'=>'Kota','city_name'=>'Bandung','postal_code'=>'40111'],
            ['city_id'=>'63','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Bandung','postal_code'=>'40311'],
            ['city_id'=>'64','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Bandung Barat','postal_code'=>'40721'],
            ['city_id'=>'65','province_id'=>'9','type'=>'Kota','city_name'=>'Bekasi','postal_code'=>'17121'],
            ['city_id'=>'66','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Bekasi','postal_code'=>'17820'],
            ['city_id'=>'67','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Bogor','postal_code'=>'16911'],
            ['city_id'=>'68','province_id'=>'9','type'=>'Kota','city_name'=>'Bogor','postal_code'=>'16119'],
            ['city_id'=>'69','province_id'=>'9','type'=>'Kota','city_name'=>'Cimahi','postal_code'=>'40511'],
            ['city_id'=>'70','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Ciamis','postal_code'=>'46219'],
            ['city_id'=>'71','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Cianjur','postal_code'=>'43219'],
            ['city_id'=>'72','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Cirebon','postal_code'=>'45619'],
            ['city_id'=>'73','province_id'=>'9','type'=>'Kota','city_name'=>'Cirebon','postal_code'=>'45111'],
            ['city_id'=>'74','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Garut','postal_code'=>'44119'],
            ['city_id'=>'75','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Indramayu','postal_code'=>'45219'],
            ['city_id'=>'76','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Karawang','postal_code'=>'41319'],
            ['city_id'=>'77','province_id'=>'9','type'=>'Kota','city_name'=>'Depok','postal_code'=>'16411'],
            ['city_id'=>'78','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Kuningan','postal_code'=>'45519'],
            ['city_id'=>'79','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Majalengka','postal_code'=>'45419'],
            ['city_id'=>'80','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Pangandaran','postal_code'=>'46396'],
            ['city_id'=>'81','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Purwakarta','postal_code'=>'41119'],
            ['city_id'=>'82','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Subang','postal_code'=>'41212'],
            ['city_id'=>'83','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Sukabumi','postal_code'=>'43311'],
            ['city_id'=>'84','province_id'=>'9','type'=>'Kota','city_name'=>'Sukabumi','postal_code'=>'43111'],
            ['city_id'=>'85','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Sumedang','postal_code'=>'45311'],
            ['city_id'=>'86','province_id'=>'9','type'=>'Kabupaten','city_name'=>'Tasikmalaya','postal_code'=>'46419'],
            ['city_id'=>'87','province_id'=>'9','type'=>'Kota','city_name'=>'Tasikmalaya','postal_code'=>'46111'],
            // ── Jawa Tengah (10) ──────────────────────────────────
            ['city_id'=>'88','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Banjarnegara','postal_code'=>'53419'],
            ['city_id'=>'89','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Banyumas','postal_code'=>'53114'],
            ['city_id'=>'90','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Batang','postal_code'=>'51219'],
            ['city_id'=>'91','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Blora','postal_code'=>'58219'],
            ['city_id'=>'92','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Boyolali','postal_code'=>'57311'],
            ['city_id'=>'93','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Brebes','postal_code'=>'52219'],
            ['city_id'=>'94','province_id'=>'10','type'=>'Kota','city_name'=>'Magelang','postal_code'=>'56111'],
            ['city_id'=>'95','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Cilacap','postal_code'=>'53219'],
            ['city_id'=>'96','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Demak','postal_code'=>'59519'],
            ['city_id'=>'97','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Grobogan','postal_code'=>'58111'],
            ['city_id'=>'98','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Jepara','postal_code'=>'59419'],
            ['city_id'=>'99','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Karanganyar','postal_code'=>'57711'],
            ['city_id'=>'100','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Kebumen','postal_code'=>'54319'],
            ['city_id'=>'101','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Kendal','postal_code'=>'51311'],
            ['city_id'=>'102','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Klaten','postal_code'=>'57411'],
            ['city_id'=>'103','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Kudus','postal_code'=>'59319'],
            ['city_id'=>'104','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Magelang','postal_code'=>'56419'],
            ['city_id'=>'105','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Pati','postal_code'=>'59119'],
            ['city_id'=>'106','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Pekalongan','postal_code'=>'51119'],
            ['city_id'=>'107','province_id'=>'10','type'=>'Kota','city_name'=>'Pekalongan','postal_code'=>'51111'],
            ['city_id'=>'108','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Pemalang','postal_code'=>'52319'],
            ['city_id'=>'109','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Purbalingga','postal_code'=>'53319'],
            ['city_id'=>'110','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Purworejo','postal_code'=>'54219'],
            ['city_id'=>'111','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Rembang','postal_code'=>'59219'],
            ['city_id'=>'112','province_id'=>'10','type'=>'Kota','city_name'=>'Salatiga','postal_code'=>'50711'],
            ['city_id'=>'113','province_id'=>'10','type'=>'Kota','city_name'=>'Semarang','postal_code'=>'50111'],
            ['city_id'=>'114','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Semarang','postal_code'=>'50511'],
            ['city_id'=>'115','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Sragen','postal_code'=>'57211'],
            ['city_id'=>'116','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Sukoharjo','postal_code'=>'57511'],
            ['city_id'=>'117','province_id'=>'10','type'=>'Kota','city_name'=>'Surakarta','postal_code'=>'57111'],
            ['city_id'=>'118','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Tegal','postal_code'=>'52419'],
            ['city_id'=>'119','province_id'=>'10','type'=>'Kota','city_name'=>'Tegal','postal_code'=>'52111'],
            ['city_id'=>'120','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Temanggung','postal_code'=>'56219'],
            ['city_id'=>'121','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Wonogiri','postal_code'=>'57611'],
            ['city_id'=>'122','province_id'=>'10','type'=>'Kabupaten','city_name'=>'Wonosobo','postal_code'=>'56319'],
            // ── Jawa Timur (11) ───────────────────────────────────
            ['city_id'=>'123','province_id'=>'11','type'=>'Kota','city_name'=>'Batu','postal_code'=>'65311'],
            ['city_id'=>'124','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Bangkalan','postal_code'=>'69119'],
            ['city_id'=>'125','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Banyuwangi','postal_code'=>'68411'],
            ['city_id'=>'126','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Blitar','postal_code'=>'66119'],
            ['city_id'=>'127','province_id'=>'11','type'=>'Kota','city_name'=>'Blitar','postal_code'=>'66111'],
            ['city_id'=>'128','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Bojonegoro','postal_code'=>'62111'],
            ['city_id'=>'129','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Bondowoso','postal_code'=>'68211'],
            ['city_id'=>'130','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Gresik','postal_code'=>'61111'],
            ['city_id'=>'131','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Jember','postal_code'=>'68111'],
            ['city_id'=>'132','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Jombang','postal_code'=>'61411'],
            ['city_id'=>'133','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Kediri','postal_code'=>'64119'],
            ['city_id'=>'134','province_id'=>'11','type'=>'Kota','city_name'=>'Kediri','postal_code'=>'64111'],
            ['city_id'=>'135','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Lamongan','postal_code'=>'62211'],
            ['city_id'=>'136','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Lumajang','postal_code'=>'67319'],
            ['city_id'=>'137','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Madiun','postal_code'=>'63119'],
            ['city_id'=>'138','province_id'=>'11','type'=>'Kota','city_name'=>'Madiun','postal_code'=>'63111'],
            ['city_id'=>'139','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Magetan','postal_code'=>'63311'],
            ['city_id'=>'140','province_id'=>'11','type'=>'Kota','city_name'=>'Malang','postal_code'=>'65111'],
            ['city_id'=>'141','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Malang','postal_code'=>'65119'],
            ['city_id'=>'142','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Mojokerto','postal_code'=>'61319'],
            ['city_id'=>'143','province_id'=>'11','type'=>'Kota','city_name'=>'Mojokerto','postal_code'=>'61311'],
            ['city_id'=>'144','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Nganjuk','postal_code'=>'64411'],
            ['city_id'=>'145','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Ngawi','postal_code'=>'63211'],
            ['city_id'=>'146','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Pacitan','postal_code'=>'63511'],
            ['city_id'=>'147','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Pamekasan','postal_code'=>'69311'],
            ['city_id'=>'148','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Pasuruan','postal_code'=>'67119'],
            ['city_id'=>'149','province_id'=>'11','type'=>'Kota','city_name'=>'Pasuruan','postal_code'=>'67111'],
            ['city_id'=>'150','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Ponorogo','postal_code'=>'63411'],
            ['city_id'=>'151','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Probolinggo','postal_code'=>'67219'],
            ['city_id'=>'152','province_id'=>'11','type'=>'Kota','city_name'=>'Probolinggo','postal_code'=>'67211'],
            ['city_id'=>'153','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Sampang','postal_code'=>'69211'],
            ['city_id'=>'154','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Sidoarjo','postal_code'=>'61211'],
            ['city_id'=>'155','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Situbondo','postal_code'=>'68311'],
            ['city_id'=>'156','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Sumenep','postal_code'=>'69411'],
            ['city_id'=>'157','province_id'=>'11','type'=>'Kota','city_name'=>'Surabaya','postal_code'=>'60111'],
            ['city_id'=>'158','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Trenggalek','postal_code'=>'66311'],
            ['city_id'=>'159','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Tuban','postal_code'=>'62311'],
            ['city_id'=>'160','province_id'=>'11','type'=>'Kabupaten','city_name'=>'Tulungagung','postal_code'=>'66211'],
            // ── Kalimantan Barat (12) ─────────────────────────────
            ['city_id'=>'161','province_id'=>'12','type'=>'Kabupaten','city_name'=>'Bengkayang','postal_code'=>'79211'],
            ['city_id'=>'162','province_id'=>'12','type'=>'Kabupaten','city_name'=>'Kapuas Hulu','postal_code'=>'78711'],
            ['city_id'=>'163','province_id'=>'12','type'=>'Kabupaten','city_name'=>'Kayong Utara','postal_code'=>'78852'],
            ['city_id'=>'164','province_id'=>'12','type'=>'Kabupaten','city_name'=>'Ketapang','postal_code'=>'78811'],
            ['city_id'=>'165','province_id'=>'12','type'=>'Kabupaten','city_name'=>'Kubu Raya','postal_code'=>'78381'],
            ['city_id'=>'166','province_id'=>'12','type'=>'Kabupaten','city_name'=>'Landak','postal_code'=>'78357'],
            ['city_id'=>'167','province_id'=>'12','type'=>'Kabupaten','city_name'=>'Melawi','postal_code'=>'78611'],
            ['city_id'=>'168','province_id'=>'12','type'=>'Kabupaten','city_name'=>'Mempawah','postal_code'=>'78912'],
            ['city_id'=>'169','province_id'=>'12','type'=>'Kabupaten','city_name'=>'Sambas','postal_code'=>'79411'],
            ['city_id'=>'170','province_id'=>'12','type'=>'Kabupaten','city_name'=>'Sanggau','postal_code'=>'78511'],
            ['city_id'=>'171','province_id'=>'12','type'=>'Kabupaten','city_name'=>'Sekadau','postal_code'=>'79511'],
            ['city_id'=>'172','province_id'=>'12','type'=>'Kabupaten','city_name'=>'Sintang','postal_code'=>'78611'],
            ['city_id'=>'173','province_id'=>'12','type'=>'Kota','city_name'=>'Pontianak','postal_code'=>'78111'],
            ['city_id'=>'174','province_id'=>'12','type'=>'Kota','city_name'=>'Singkawang','postal_code'=>'79111'],
            // ── Kalimantan Selatan (13) ───────────────────────────
            ['city_id'=>'175','province_id'=>'13','type'=>'Kabupaten','city_name'=>'Balangan','postal_code'=>'71611'],
            ['city_id'=>'176','province_id'=>'13','type'=>'Kota','city_name'=>'Banjarbaru','postal_code'=>'70711'],
            ['city_id'=>'177','province_id'=>'13','type'=>'Kota','city_name'=>'Banjarmasin','postal_code'=>'70111'],
            ['city_id'=>'178','province_id'=>'13','type'=>'Kabupaten','city_name'=>'Banjar','postal_code'=>'70611'],
            ['city_id'=>'179','province_id'=>'13','type'=>'Kabupaten','city_name'=>'Barito Kuala','postal_code'=>'70511'],
            ['city_id'=>'180','province_id'=>'13','type'=>'Kabupaten','city_name'=>'Hulu Sungai Selatan','postal_code'=>'71211'],
            ['city_id'=>'181','province_id'=>'13','type'=>'Kabupaten','city_name'=>'Hulu Sungai Tengah','postal_code'=>'71311'],
            ['city_id'=>'182','province_id'=>'13','type'=>'Kabupaten','city_name'=>'Hulu Sungai Utara','postal_code'=>'71411'],
            ['city_id'=>'183','province_id'=>'13','type'=>'Kabupaten','city_name'=>'Kotabaru','postal_code'=>'72111'],
            ['city_id'=>'184','province_id'=>'13','type'=>'Kabupaten','city_name'=>'Tabalong','postal_code'=>'71511'],
            ['city_id'=>'185','province_id'=>'13','type'=>'Kabupaten','city_name'=>'Tanah Bumbu','postal_code'=>'72211'],
            ['city_id'=>'186','province_id'=>'13','type'=>'Kabupaten','city_name'=>'Tanah Laut','postal_code'=>'70811'],
            ['city_id'=>'187','province_id'=>'13','type'=>'Kabupaten','city_name'=>'Tapin','postal_code'=>'71119'],
            // ── Kalimantan Tengah (14) ────────────────────────────
            ['city_id'=>'188','province_id'=>'14','type'=>'Kabupaten','city_name'=>'Barito Selatan','postal_code'=>'73711'],
            ['city_id'=>'189','province_id'=>'14','type'=>'Kabupaten','city_name'=>'Barito Timur','postal_code'=>'73671'],
            ['city_id'=>'190','province_id'=>'14','type'=>'Kabupaten','city_name'=>'Barito Utara','postal_code'=>'73811'],
            ['city_id'=>'191','province_id'=>'14','type'=>'Kabupaten','city_name'=>'Gunung Mas','postal_code'=>'74511'],
            ['city_id'=>'192','province_id'=>'14','type'=>'Kabupaten','city_name'=>'Kapuas','postal_code'=>'73511'],
            ['city_id'=>'193','province_id'=>'14','type'=>'Kabupaten','city_name'=>'Katingan','postal_code'=>'74411'],
            ['city_id'=>'194','province_id'=>'14','type'=>'Kabupaten','city_name'=>'Kotawaringin Barat','postal_code'=>'74111'],
            ['city_id'=>'195','province_id'=>'14','type'=>'Kabupaten','city_name'=>'Kotawaringin Timur','postal_code'=>'74311'],
            ['city_id'=>'196','province_id'=>'14','type'=>'Kabupaten','city_name'=>'Lamandau','postal_code'=>'74662'],
            ['city_id'=>'197','province_id'=>'14','type'=>'Kabupaten','city_name'=>'Murung Raya','postal_code'=>'73911'],
            ['city_id'=>'198','province_id'=>'14','type'=>'Kota','city_name'=>'Palangka Raya','postal_code'=>'73111'],
            ['city_id'=>'199','province_id'=>'14','type'=>'Kabupaten','city_name'=>'Pulang Pisau','postal_code'=>'73611'],
            ['city_id'=>'200','province_id'=>'14','type'=>'Kabupaten','city_name'=>'Seruyan','postal_code'=>'74211'],
            ['city_id'=>'201','province_id'=>'14','type'=>'Kabupaten','city_name'=>'Sukamara','postal_code'=>'74172'],
            // ── Kalimantan Timur (15) ─────────────────────────────
            ['city_id'=>'202','province_id'=>'15','type'=>'Kota','city_name'=>'Balikpapan','postal_code'=>'76111'],
            ['city_id'=>'203','province_id'=>'15','type'=>'Kabupaten','city_name'=>'Berau','postal_code'=>'77311'],
            ['city_id'=>'204','province_id'=>'15','type'=>'Kabupaten','city_name'=>'Kutai Barat','postal_code'=>'75519'],
            ['city_id'=>'205','province_id'=>'15','type'=>'Kabupaten','city_name'=>'Kutai Kartanegara','postal_code'=>'75511'],
            ['city_id'=>'206','province_id'=>'15','type'=>'Kabupaten','city_name'=>'Kutai Timur','postal_code'=>'75611'],
            ['city_id'=>'207','province_id'=>'15','type'=>'Kabupaten','city_name'=>'Mahakam Hulu','postal_code'=>'75654'],
            ['city_id'=>'208','province_id'=>'15','type'=>'Kabupaten','city_name'=>'Paser','postal_code'=>'76211'],
            ['city_id'=>'209','province_id'=>'15','type'=>'Kabupaten','city_name'=>'Penajam Paser Utara','postal_code'=>'76141'],
            ['city_id'=>'210','province_id'=>'15','type'=>'Kota','city_name'=>'Samarinda','postal_code'=>'75111'],
            ['city_id'=>'211','province_id'=>'15','type'=>'Kota','city_name'=>'Bontang','postal_code'=>'75311'],
            // ── Kalimantan Utara (16) ─────────────────────────────
            ['city_id'=>'212','province_id'=>'16','type'=>'Kabupaten','city_name'=>'Bulungan','postal_code'=>'77211'],
            ['city_id'=>'213','province_id'=>'16','type'=>'Kabupaten','city_name'=>'Malinau','postal_code'=>'77511'],
            ['city_id'=>'214','province_id'=>'16','type'=>'Kabupaten','city_name'=>'Nunukan','postal_code'=>'77411'],
            ['city_id'=>'215','province_id'=>'16','type'=>'Kabupaten','city_name'=>'Tana Tidung','postal_code'=>'77311'],
            ['city_id'=>'216','province_id'=>'16','type'=>'Kota','city_name'=>'Tarakan','postal_code'=>'77111'],
            // ── Kepulauan Riau (17) ───────────────────────────────
            ['city_id'=>'217','province_id'=>'17','type'=>'Kabupaten','city_name'=>'Bintan','postal_code'=>'29119'],
            ['city_id'=>'218','province_id'=>'17','type'=>'Kota','city_name'=>'Batam','postal_code'=>'29411'],
            ['city_id'=>'219','province_id'=>'17','type'=>'Kabupaten','city_name'=>'Karimun','postal_code'=>'29611'],
            ['city_id'=>'220','province_id'=>'17','type'=>'Kabupaten','city_name'=>'Kepulauan Anambas','postal_code'=>'29791'],
            ['city_id'=>'221','province_id'=>'17','type'=>'Kabupaten','city_name'=>'Lingga','postal_code'=>'29811'],
            ['city_id'=>'222','province_id'=>'17','type'=>'Kabupaten','city_name'=>'Natuna','postal_code'=>'29711'],
            ['city_id'=>'223','province_id'=>'17','type'=>'Kota','city_name'=>'Tanjung Pinang','postal_code'=>'29111'],
            // ── Lampung (18) ──────────────────────────────────────
            ['city_id'=>'224','province_id'=>'18','type'=>'Kabupaten','city_name'=>'Lampung Barat','postal_code'=>'34814'],
            ['city_id'=>'225','province_id'=>'18','type'=>'Kabupaten','city_name'=>'Lampung Selatan','postal_code'=>'35514'],
            ['city_id'=>'226','province_id'=>'18','type'=>'Kabupaten','city_name'=>'Lampung Tengah','postal_code'=>'34111'],
            ['city_id'=>'227','province_id'=>'18','type'=>'Kabupaten','city_name'=>'Lampung Timur','postal_code'=>'34319'],
            ['city_id'=>'228','province_id'=>'18','type'=>'Kabupaten','city_name'=>'Lampung Utara','postal_code'=>'34511'],
            ['city_id'=>'229','province_id'=>'18','type'=>'Kabupaten','city_name'=>'Mesuji','postal_code'=>'34914'],
            ['city_id'=>'230','province_id'=>'18','type'=>'Kabupaten','city_name'=>'Pesawaran','postal_code'=>'35317'],
            ['city_id'=>'231','province_id'=>'18','type'=>'Kabupaten','city_name'=>'Pesisir Barat','postal_code'=>'34974'],
            ['city_id'=>'232','province_id'=>'18','type'=>'Kabupaten','city_name'=>'Pringsewu','postal_code'=>'35319'],
            ['city_id'=>'233','province_id'=>'18','type'=>'Kabupaten','city_name'=>'Tanggamus','postal_code'=>'35619'],
            ['city_id'=>'234','province_id'=>'18','type'=>'Kabupaten','city_name'=>'Tulang Bawang','postal_code'=>'34716'],
            ['city_id'=>'235','province_id'=>'18','type'=>'Kabupaten','city_name'=>'Tulang Bawang Barat','postal_code'=>'34814'],
            ['city_id'=>'236','province_id'=>'18','type'=>'Kabupaten','city_name'=>'Way Kanan','postal_code'=>'34711'],
            ['city_id'=>'237','province_id'=>'18','type'=>'Kota','city_name'=>'Bandar Lampung','postal_code'=>'35119'],
            ['city_id'=>'238','province_id'=>'18','type'=>'Kota','city_name'=>'Metro','postal_code'=>'34111'],
            // ── Maluku (19) ───────────────────────────────────────
            ['city_id'=>'239','province_id'=>'19','type'=>'Kabupaten','city_name'=>'Buru','postal_code'=>'97371'],
            ['city_id'=>'240','province_id'=>'19','type'=>'Kabupaten','city_name'=>'Buru Selatan','postal_code'=>'97372'],
            ['city_id'=>'241','province_id'=>'19','type'=>'Kabupaten','city_name'=>'Kepulauan Aru','postal_code'=>'97671'],
            ['city_id'=>'242','province_id'=>'19','type'=>'Kabupaten','city_name'=>'Maluku Barat Daya','postal_code'=>'97571'],
            ['city_id'=>'243','province_id'=>'19','type'=>'Kabupaten','city_name'=>'Maluku Tengah','postal_code'=>'97511'],
            ['city_id'=>'244','province_id'=>'19','type'=>'Kabupaten','city_name'=>'Maluku Tenggara','postal_code'=>'97611'],
            ['city_id'=>'245','province_id'=>'19','type'=>'Kabupaten','city_name'=>'Maluku Tenggara Barat','postal_code'=>'97651'],
            ['city_id'=>'246','province_id'=>'19','type'=>'Kota','city_name'=>'Ambon','postal_code'=>'97111'],
            ['city_id'=>'247','province_id'=>'19','type'=>'Kabupaten','city_name'=>'Seram Bagian Barat','postal_code'=>'97411'],
            ['city_id'=>'248','province_id'=>'19','type'=>'Kabupaten','city_name'=>'Seram Bagian Timur','postal_code'=>'97471'],
            ['city_id'=>'249','province_id'=>'19','type'=>'Kota','city_name'=>'Tual','postal_code'=>'97611'],
            // ── Maluku Utara (20) ─────────────────────────────────
            ['city_id'=>'250','province_id'=>'20','type'=>'Kabupaten','city_name'=>'Halmahera Barat','postal_code'=>'97711'],
            ['city_id'=>'251','province_id'=>'20','type'=>'Kabupaten','city_name'=>'Halmahera Selatan','postal_code'=>'97811'],
            ['city_id'=>'252','province_id'=>'20','type'=>'Kabupaten','city_name'=>'Halmahera Tengah','postal_code'=>'97751'],
            ['city_id'=>'253','province_id'=>'20','type'=>'Kabupaten','city_name'=>'Halmahera Timur','postal_code'=>'97762'],
            ['city_id'=>'254','province_id'=>'20','type'=>'Kabupaten','city_name'=>'Halmahera Utara','postal_code'=>'97761'],
            ['city_id'=>'255','province_id'=>'20','type'=>'Kabupaten','city_name'=>'Kepulauan Sula','postal_code'=>'97991'],
            ['city_id'=>'256','province_id'=>'20','type'=>'Kabupaten','city_name'=>'Pulau Morotai','postal_code'=>'97771'],
            ['city_id'=>'257','province_id'=>'20','type'=>'Kabupaten','city_name'=>'Pulau Taliabu','postal_code'=>'97872'],
            ['city_id'=>'258','province_id'=>'20','type'=>'Kota','city_name'=>'Ternate','postal_code'=>'97711'],
            ['city_id'=>'259','province_id'=>'20','type'=>'Kota','city_name'=>'Tidore Kepulauan','postal_code'=>'97811'],
            // ── NTB (21) ──────────────────────────────────────────
            ['city_id'=>'260','province_id'=>'21','type'=>'Kabupaten','city_name'=>'Bima','postal_code'=>'84119'],
            ['city_id'=>'261','province_id'=>'21','type'=>'Kota','city_name'=>'Bima','postal_code'=>'84111'],
            ['city_id'=>'262','province_id'=>'21','type'=>'Kabupaten','city_name'=>'Dompu','postal_code'=>'84211'],
            ['city_id'=>'263','province_id'=>'21','type'=>'Kabupaten','city_name'=>'Lombok Barat','postal_code'=>'83311'],
            ['city_id'=>'264','province_id'=>'21','type'=>'Kabupaten','city_name'=>'Lombok Tengah','postal_code'=>'83511'],
            ['city_id'=>'265','province_id'=>'21','type'=>'Kabupaten','city_name'=>'Lombok Timur','postal_code'=>'83611'],
            ['city_id'=>'266','province_id'=>'21','type'=>'Kabupaten','city_name'=>'Lombok Utara','postal_code'=>'83352'],
            ['city_id'=>'267','province_id'=>'21','type'=>'Kota','city_name'=>'Mataram','postal_code'=>'83111'],
            ['city_id'=>'268','province_id'=>'21','type'=>'Kabupaten','city_name'=>'Sumbawa','postal_code'=>'84311'],
            ['city_id'=>'269','province_id'=>'21','type'=>'Kabupaten','city_name'=>'Sumbawa Barat','postal_code'=>'84411'],
            // ── NTT (22) ──────────────────────────────────────────
            ['city_id'=>'270','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Alor','postal_code'=>'85811'],
            ['city_id'=>'271','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Belu','postal_code'=>'85711'],
            ['city_id'=>'272','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Ende','postal_code'=>'86311'],
            ['city_id'=>'273','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Flores Timur','postal_code'=>'86211'],
            ['city_id'=>'274','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Kupang','postal_code'=>'85119'],
            ['city_id'=>'275','province_id'=>'22','type'=>'Kota','city_name'=>'Kupang','postal_code'=>'85111'],
            ['city_id'=>'276','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Lembata','postal_code'=>'86611'],
            ['city_id'=>'277','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Malaka','postal_code'=>'85762'],
            ['city_id'=>'278','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Manggarai','postal_code'=>'86511'],
            ['city_id'=>'279','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Manggarai Barat','postal_code'=>'86554'],
            ['city_id'=>'280','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Manggarai Timur','postal_code'=>'86511'],
            ['city_id'=>'281','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Nagekeo','postal_code'=>'86411'],
            ['city_id'=>'282','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Ngada','postal_code'=>'86411'],
            ['city_id'=>'283','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Rote Ndao','postal_code'=>'85981'],
            ['city_id'=>'284','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Sabu Raijua','postal_code'=>'85391'],
            ['city_id'=>'285','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Sikka','postal_code'=>'86111'],
            ['city_id'=>'286','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Sumba Barat','postal_code'=>'87211'],
            ['city_id'=>'287','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Sumba Barat Daya','postal_code'=>'87252'],
            ['city_id'=>'288','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Sumba Tengah','postal_code'=>'87252'],
            ['city_id'=>'289','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Sumba Timur','postal_code'=>'87111'],
            ['city_id'=>'290','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Timor Tengah Selatan','postal_code'=>'85511'],
            ['city_id'=>'291','province_id'=>'22','type'=>'Kabupaten','city_name'=>'Timor Tengah Utara','postal_code'=>'85611'],
            // ── Papua (23) ────────────────────────────────────────
            ['city_id'=>'292','province_id'=>'23','type'=>'Kabupaten','city_name'=>'Asmat','postal_code'=>'99777'],
            ['city_id'=>'293','province_id'=>'23','type'=>'Kabupaten','city_name'=>'Biak Numfor','postal_code'=>'98111'],
            ['city_id'=>'294','province_id'=>'23','type'=>'Kabupaten','city_name'=>'Boven Digoel','postal_code'=>'99663'],
            ['city_id'=>'298','province_id'=>'23','type'=>'Kabupaten','city_name'=>'Jayapura','postal_code'=>'99351'],
            ['city_id'=>'299','province_id'=>'23','type'=>'Kota','city_name'=>'Jayapura','postal_code'=>'99111'],
            ['city_id'=>'306','province_id'=>'23','type'=>'Kabupaten','city_name'=>'Merauke','postal_code'=>'99611'],
            ['city_id'=>'307','province_id'=>'23','type'=>'Kabupaten','city_name'=>'Mimika','postal_code'=>'99961'],
            ['city_id'=>'308','province_id'=>'23','type'=>'Kabupaten','city_name'=>'Nabire','postal_code'=>'98811'],
            // ── Papua Barat (24) ──────────────────────────────────
            ['city_id'=>'320','province_id'=>'24','type'=>'Kabupaten','city_name'=>'Fakfak','postal_code'=>'98611'],
            ['city_id'=>'322','province_id'=>'24','type'=>'Kabupaten','city_name'=>'Manokwari','postal_code'=>'98311'],
            ['city_id'=>'327','province_id'=>'24','type'=>'Kabupaten','city_name'=>'Sorong','postal_code'=>'98411'],
            ['city_id'=>'328','province_id'=>'24','type'=>'Kota','city_name'=>'Sorong','postal_code'=>'98411'],
            // ── Riau (25) ─────────────────────────────────────────
            ['city_id'=>'333','province_id'=>'25','type'=>'Kabupaten','city_name'=>'Bengkalis','postal_code'=>'28711'],
            ['city_id'=>'334','province_id'=>'25','type'=>'Kabupaten','city_name'=>'Indragiri Hilir','postal_code'=>'29211'],
            ['city_id'=>'335','province_id'=>'25','type'=>'Kabupaten','city_name'=>'Indragiri Hulu','postal_code'=>'29311'],
            ['city_id'=>'336','province_id'=>'25','type'=>'Kabupaten','city_name'=>'Kampar','postal_code'=>'28411'],
            ['city_id'=>'340','province_id'=>'25','type'=>'Kota','city_name'=>'Dumai','postal_code'=>'28811'],
            ['city_id'=>'341','province_id'=>'25','type'=>'Kota','city_name'=>'Pekanbaru','postal_code'=>'28111'],
            ['city_id'=>'343','province_id'=>'25','type'=>'Kabupaten','city_name'=>'Rokan Hulu','postal_code'=>'28511'],
            ['city_id'=>'344','province_id'=>'25','type'=>'Kabupaten','city_name'=>'Siak','postal_code'=>'28611'],
            // ── Sulawesi Barat (26) ───────────────────────────────
            ['city_id'=>'345','province_id'=>'26','type'=>'Kabupaten','city_name'=>'Majene','postal_code'=>'91411'],
            ['city_id'=>'347','province_id'=>'26','type'=>'Kabupaten','city_name'=>'Mamuju','postal_code'=>'91511'],
            ['city_id'=>'350','province_id'=>'26','type'=>'Kabupaten','city_name'=>'Polewali Mandar','postal_code'=>'91311'],
            // ── Sulawesi Selatan (27) ─────────────────────────────
            ['city_id'=>'351','province_id'=>'27','type'=>'Kabupaten','city_name'=>'Bantaeng','postal_code'=>'92411'],
            ['city_id'=>'352','province_id'=>'27','type'=>'Kabupaten','city_name'=>'Barru','postal_code'=>'90711'],
            ['city_id'=>'353','province_id'=>'27','type'=>'Kabupaten','city_name'=>'Bone','postal_code'=>'92711'],
            ['city_id'=>'354','province_id'=>'27','type'=>'Kabupaten','city_name'=>'Bulukumba','postal_code'=>'92511'],
            ['city_id'=>'356','province_id'=>'27','type'=>'Kabupaten','city_name'=>'Gowa','postal_code'=>'92111'],
            ['city_id'=>'358','province_id'=>'27','type'=>'Kota','city_name'=>'Makassar','postal_code'=>'90111'],
            ['city_id'=>'363','province_id'=>'27','type'=>'Kota','city_name'=>'Palopo','postal_code'=>'91911'],
            ['city_id'=>'364','province_id'=>'27','type'=>'Kota','city_name'=>'Parepare','postal_code'=>'91111'],
            ['city_id'=>'374','province_id'=>'27','type'=>'Kabupaten','city_name'=>'Wajo','postal_code'=>'90911'],
            // ── Sulawesi Tengah (28) ──────────────────────────────
            ['city_id'=>'375','province_id'=>'28','type'=>'Kabupaten','city_name'=>'Banggai','postal_code'=>'94711'],
            ['city_id'=>'379','province_id'=>'28','type'=>'Kabupaten','city_name'=>'Donggala','postal_code'=>'94341'],
            ['city_id'=>'383','province_id'=>'28','type'=>'Kota','city_name'=>'Palu','postal_code'=>'94111'],
            ['city_id'=>'384','province_id'=>'28','type'=>'Kabupaten','city_name'=>'Poso','postal_code'=>'94611'],
            // ── Sulawesi Tenggara (29) ────────────────────────────
            ['city_id'=>'393','province_id'=>'29','type'=>'Kabupaten','city_name'=>'Kolaka','postal_code'=>'93511'],
            ['city_id'=>'400','province_id'=>'29','type'=>'Kota','city_name'=>'Bau-Bau','postal_code'=>'93711'],
            ['city_id'=>'401','province_id'=>'29','type'=>'Kota','city_name'=>'Kendari','postal_code'=>'93111'],
            ['city_id'=>'402','province_id'=>'29','type'=>'Kabupaten','city_name'=>'Muna','postal_code'=>'93611'],
            // ── Sulawesi Utara (30) ───────────────────────────────
            ['city_id'=>'405','province_id'=>'30','type'=>'Kabupaten','city_name'=>'Bolaang Mongondow','postal_code'=>'95711'],
            ['city_id'=>'409','province_id'=>'30','type'=>'Kabupaten','city_name'=>'Kepulauan Sangihe','postal_code'=>'95819'],
            ['city_id'=>'412','province_id'=>'30','type'=>'Kota','city_name'=>'Kotamobagu','postal_code'=>'95711'],
            ['city_id'=>'413','province_id'=>'30','type'=>'Kabupaten','city_name'=>'Minahasa','postal_code'=>'95619'],
            ['city_id'=>'417','province_id'=>'30','type'=>'Kota','city_name'=>'Bitung','postal_code'=>'95511'],
            ['city_id'=>'418','province_id'=>'30','type'=>'Kota','city_name'=>'Manado','postal_code'=>'95111'],
            ['city_id'=>'419','province_id'=>'30','type'=>'Kota','city_name'=>'Tomohon','postal_code'=>'95411'],
            // ── Sumatera Barat (31) ───────────────────────────────
            ['city_id'=>'420','province_id'=>'31','type'=>'Kabupaten','city_name'=>'Agam','postal_code'=>'26411'],
            ['city_id'=>'424','province_id'=>'31','type'=>'Kota','city_name'=>'Bukittinggi','postal_code'=>'26111'],
            ['city_id'=>'428','province_id'=>'31','type'=>'Kota','city_name'=>'Padang','postal_code'=>'25111'],
            ['city_id'=>'434','province_id'=>'31','type'=>'Kota','city_name'=>'Payakumbuh','postal_code'=>'26211'],
            ['city_id'=>'437','province_id'=>'31','type'=>'Kabupaten','city_name'=>'Tanah Datar','postal_code'=>'27211'],
            // ── Sumatera Selatan (32) ─────────────────────────────
            ['city_id'=>'438','province_id'=>'32','type'=>'Kabupaten','city_name'=>'Banyuasin','postal_code'=>'30711'],
            ['city_id'=>'440','province_id'=>'32','type'=>'Kabupaten','city_name'=>'Lahat','postal_code'=>'31411'],
            ['city_id'=>'441','province_id'=>'32','type'=>'Kabupaten','city_name'=>'Muara Enim','postal_code'=>'31311'],
            ['city_id'=>'450','province_id'=>'32','type'=>'Kota','city_name'=>'Lubuk Linggau','postal_code'=>'31611'],
            ['city_id'=>'452','province_id'=>'32','type'=>'Kota','city_name'=>'Palembang','postal_code'=>'30111'],
            ['city_id'=>'453','province_id'=>'32','type'=>'Kota','city_name'=>'Prabumulih','postal_code'=>'31111'],
            // ── Sumatera Utara (33) ───────────────────────────────
            ['city_id'=>'455','province_id'=>'33','type'=>'Kabupaten','city_name'=>'Asahan','postal_code'=>'21211'],
            ['city_id'=>'458','province_id'=>'33','type'=>'Kabupaten','city_name'=>'Deli Serdang','postal_code'=>'20511'],
            ['city_id'=>'460','province_id'=>'33','type'=>'Kabupaten','city_name'=>'Karo','postal_code'=>'22119'],
            ['city_id'=>'464','province_id'=>'33','type'=>'Kabupaten','city_name'=>'Langkat','postal_code'=>'20811'],
            ['city_id'=>'480','province_id'=>'33','type'=>'Kota','city_name'=>'Binjai','postal_code'=>'20711'],
            ['city_id'=>'482','province_id'=>'33','type'=>'Kota','city_name'=>'Medan','postal_code'=>'20111'],
            ['city_id'=>'484','province_id'=>'33','type'=>'Kota','city_name'=>'Pematang Siantar','postal_code'=>'21111'],
            ['city_id'=>'485','province_id'=>'33','type'=>'Kota','city_name'=>'Sibolga','postal_code'=>'22511'],
            ['city_id'=>'487','province_id'=>'33','type'=>'Kota','city_name'=>'Tebing Tinggi','postal_code'=>'20611'],
            // ── Aceh (34) ─────────────────────────────────────────
            ['city_id'=>'488','province_id'=>'34','type'=>'Kabupaten','city_name'=>'Aceh Barat','postal_code'=>'23611'],
            ['city_id'=>'490','province_id'=>'34','type'=>'Kabupaten','city_name'=>'Aceh Besar','postal_code'=>'23951'],
            ['city_id'=>'492','province_id'=>'34','type'=>'Kabupaten','city_name'=>'Aceh Selatan','postal_code'=>'23711'],
            ['city_id'=>'495','province_id'=>'34','type'=>'Kabupaten','city_name'=>'Aceh Tengah','postal_code'=>'24511'],
            ['city_id'=>'498','province_id'=>'34','type'=>'Kabupaten','city_name'=>'Aceh Utara','postal_code'=>'24311'],
            ['city_id'=>'500','province_id'=>'34','type'=>'Kabupaten','city_name'=>'Bireuen','postal_code'=>'24211'],
            ['city_id'=>'503','province_id'=>'34','type'=>'Kabupaten','city_name'=>'Pidie','postal_code'=>'24151'],
            ['city_id'=>'506','province_id'=>'34','type'=>'Kota','city_name'=>'Banda Aceh','postal_code'=>'23111'],
            ['city_id'=>'507','province_id'=>'34','type'=>'Kota','city_name'=>'Langsa','postal_code'=>'24411'],
            ['city_id'=>'508','province_id'=>'34','type'=>'Kota','city_name'=>'Lhokseumawe','postal_code'=>'24311'],
            ['city_id'=>'509','province_id'=>'34','type'=>'Kota','city_name'=>'Sabang','postal_code'=>'23511'],
            ['city_id'=>'510','province_id'=>'34','type'=>'Kota','city_name'=>'Subulussalam','postal_code'=>'24781'],
        ];
    }
}