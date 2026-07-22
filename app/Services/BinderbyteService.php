<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BinderbyteService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.binderbyte.com/v1';
    protected string $wilayahUrl = 'https://api.binderbyte.com/wilayah';

    // Kota asal museum (Purwakarta) — nama kota untuk Binderbyte
    protected string $originCity = 'purwakarta';

    // Kurir yang tersedia — bisa ditambah sesuai kebutuhan
    // Kode kurir Binderbyte: jne, jnt, sicepat, ide, pos, tiki, anteraja, lion, sap, ninja, wahana
    protected array $availableCouriers = ['jne', 'jnt', 'sicepat', 'ide', 'pos', 'tiki', 'anteraja'];

    public function __construct()
    {
        $this->apiKey    = config('services.binderbyte.api_key');
        $this->originCity = config('services.binderbyte.origin_city', 'purwakarta');
    }

    // ═══════════════════════════════════════════════════════
    // WILAYAH
    // ═══════════════════════════════════════════════════════

    /**
     * Ambil semua provinsi (cache 30 hari)
     */
    public function getProvinces(): array
    {
        return Cache::remember('binderbyte_provinces', 86400 * 30, function () {
            try {
                $response = Http::timeout(10)->get("{$this->wilayahUrl}/provinsi", [
                    'api_key' => $this->apiKey,
                ]);

                if ($response->successful()) {
                    $data = $response->json('value', []);
                    if (!empty($data)) {
                        return $data; // [['id'=>'11','name'=>'ACEH'], ...]
                    }
                }

                Log::warning('Binderbyte getProvinces gagal', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            } catch (\Exception $e) {
                Log::warning('Binderbyte getProvinces exception', ['error' => $e->getMessage()]);
            }

            return [];
        });
    }

    /**
     * Ambil kota/kabupaten berdasarkan id_provinsi (cache 30 hari)
     */
    public function getCitiesByProvince(string $provinceId): array
    {
        return Cache::remember("binderbyte_cities_{$provinceId}", 86400 * 30, function () use ($provinceId) {
            try {
                $response = Http::timeout(10)->get("{$this->wilayahUrl}/kabupaten", [
                    'api_key'     => $this->apiKey,
                    'id_provinsi' => $provinceId,
                ]);

                if ($response->successful()) {
                    $data = $response->json('value', []);
                    if (!empty($data)) {
                        return $data; // [['id'=>'3201','id_provinsi'=>'32','name'=>'KAB. BOGOR'], ...]
                    }
                }

                Log::warning('Binderbyte getCities gagal', [
                    'province_id' => $provinceId,
                    'status'      => $response->status(),
                ]);
            } catch (\Exception $e) {
                Log::warning('Binderbyte getCities exception', ['error' => $e->getMessage()]);
            }

            return [];
        });
    }

    /**
     * Ambil semua kota dikelompokkan per province_id (untuk blade)
     */
    public function getAllCitiesGrouped(): array
    {
        return Cache::remember('binderbyte_all_cities_grouped', 86400 * 30, function () {
            $provinces = $this->getProvinces();
            $grouped   = [];

            foreach ($provinces as $province) {
                $cities = $this->getCitiesByProvince($province['id']);
                $grouped[$province['id']] = $cities;
            }

            return $grouped;
        });
    }

    // ═══════════════════════════════════════════════════════
    // ONGKIR
    // ═══════════════════════════════════════════════════════

    /**
     * Hitung ongkir ke kota tujuan (semua kurir)
     * $destinationCity: nama kota tujuan (contoh: "jakarta pusat")
     * $weightGram: berat dalam gram
     */
    public function calculateAllCouriers(string $destinationCity, int $weightGram): array
    {
        // Normalisasi nama kota — Binderbyte pakai nama teks
        $destination = $this->normalizeCityName($destinationCity);
        $weightKg    = max(0.1, round($weightGram / 1000, 2));

        // Cek apakah tujuan sama dengan asal (gratis)
        if ($this->isSameCity($destination)) {
            return [];
        }

        $cacheKey = 'binderbyte_cost_' . md5($destination . '_' . $weightKg . '_' . implode(',', $this->availableCouriers));

        return Cache::remember($cacheKey, 3600, function () use ($destination, $weightKg) {
            $allServices = [];

            foreach ($this->availableCouriers as $courier) {
                $services    = $this->calculateCost($destination, $weightKg, $courier);
                $allServices = array_merge($allServices, $services);
            }

            // Urutkan dari termurah
            usort($allServices, fn($a, $b) => $a['cost'] <=> $b['cost']);

            return $allServices;
        });
    }

    /**
     * Hitung ongkir untuk satu kurir
     */
    public function calculateCost(string $destination, float $weightKg, string $courier): array
    {
        try {
            $response = Http::timeout(15)->post("{$this->baseUrl}/cost", [
                'api_key'     => $this->apiKey,
                'origin'      => $this->originCity,
                'destination' => $destination,
                'weight'      => $weightKg,
                'courier'     => $courier,
            ]);

            if ($response->successful()) {
                $body = $response->json();

                if (($body['code'] ?? '') === '200' && isset($body['data']['results'])) {
                    return $this->flattenCourierResults($body['data']['results']);
                }
            }

            Log::info('Binderbyte calculateCost response', [
                'courier'     => $courier,
                'destination' => $destination,
                'status'      => $response->status(),
                'body'        => substr($response->body(), 0, 500),
            ]);
        } catch (\Exception $e) {
            Log::warning('Binderbyte calculateCost exception', [
                'courier' => $courier,
                'error'   => $e->getMessage(),
            ]);
        }

        return [];
    }

    /**
     * Cek apakah tujuan sama dengan kota asal (gratis/ambil sendiri)
     */
    public function isSameCity(string $destination): bool
    {
        return str_contains(strtolower($destination), strtolower($this->originCity));
    }

    public function getOriginCity(): string
    {
        return $this->originCity;
    }

    // ═══════════════════════════════════════════════════════
    // TRACKING
    // ═══════════════════════════════════════════════════════

    /**
     * Track resi (dengan cache 15 menit)
     */
    public function track(string $awb, string $courier): array
    {
        $courierCode = $this->resolveCourierCode($courier);

        if (!$courierCode) {
            return [
                'success' => false,
                'message' => 'Kurir "' . $courier . '" tidak didukung untuk tracking otomatis.',
                'data'    => null,
            ];
        }

        $cleanAwb = $this->cleanAwb($awb);
        $cacheKey = 'binderbyte_track_' . md5($cleanAwb . '_' . $courierCode);

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($cleanAwb, $courierCode) {
            return $this->hitTrackingApi($cleanAwb, $courierCode);
        });
    }

    /**
     * Force refresh tracking (bypass cache)
     */
    public function refresh(string $awb, string $courier): array
    {
        $courierCode = $this->resolveCourierCode($courier);

        if (!$courierCode) {
            return ['success' => false, 'message' => 'Kurir tidak didukung.', 'data' => null];
        }

        $cleanAwb = $this->cleanAwb($awb);
        $cacheKey = 'binderbyte_track_' . md5($cleanAwb . '_' . $courierCode);
        Cache::forget($cacheKey);

        return $this->hitTrackingApi($cleanAwb, $courierCode);
    }

    /**
     * Hit Binderbyte tracking API
     */
    protected function hitTrackingApi(string $awb, string $courierCode): array
    {
        try {
            $response = Http::timeout(30)->get("{$this->baseUrl}/track", [
                'api_key' => $this->apiKey,
                'courier' => $courierCode,
                'awb'     => $awb,
                'number'  => $awb,
            ]);

            Log::info('Binderbyte tracking request', [
                'awb'     => $awb,
                'courier' => $courierCode,
                'status'  => $response->status(),
            ]);

            if ($response->successful()) {
                $body = $response->json();

                if (in_array($body['code'] ?? $body['status'] ?? '', ['200', 200]) && isset($body['data'])) {
                    return [
                        'success' => true,
                        'message' => 'OK',
                        'data'    => $this->parseTrackingResponse($body['data'], $courierCode),
                    ];
                }

                $errorMsg = $body['message'] ?? 'Data tracking tidak ditemukan';
                return [
                    'success' => false,
                    'message' => $response->status() === 404
                        ? 'Nomor resi tidak ditemukan. Pastikan nomor resi sudah aktif (biasanya 15–30 menit setelah paket diserahkan ke kurir).'
                        : $this->friendlyError($errorMsg),
                    'data'    => null,
                ];
            }

            return [
                'success' => false,
                'message' => $this->httpErrorMessage($response->status()),
                'data'    => null,
            ];
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::warning('Binderbyte connection error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Koneksi ke server tracking gagal. Coba lagi nanti.',
                'data'    => null,
            ];
        } catch (\Throwable $e) {
            Log::error('Binderbyte tracking exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.',
                'data'    => null,
            ];
        }
    }

    /**
     * Parse response tracking dari Binderbyte
     */
    protected function parseTrackingResponse(array $data, string $courierCode): array
    {
        $summary  = $data['summary'] ?? [];
        $detail   = $data['detail'] ?? [];
        $history  = $data['history'] ?? [];

        $parsedHistory = collect($history)->map(fn($item) => [
            'datetime'    => trim(($item['date'] ?? '') . ' ' . ($item['time'] ?? '')),
            'time'        => $item['time'] ?? '',
            'description' => $item['desc'] ?? '',
            'city'        => $item['location'] ?? '',
        ])->values()->toArray();

        $status      = $summary['status'] ?? 'Dalam Perjalanan';
        $isDelivered = strtolower($status) === 'delivered' || str_contains(strtolower($status), 'terima');

        if ($isDelivered) {
            $status = 'Diterima';
        }

        return [
            'courier'      => strtoupper($courierCode),
            'awb'          => $summary['awb'] ?? $detail['awb'] ?? '',
            'service'      => $summary['service'] ?? '',
            'status'       => $status,
            'origin'       => $summary['origin'] ?? '',
            'destination'  => $summary['destination'] ?? '',
            'shipper'      => $detail['shipper'] ?? '',
            'receiver'     => $detail['receiver'] ?? '',
            'weight'       => $detail['weight'] ?? '',
            'delivered'    => $isDelivered,
            'pod_receiver' => $summary['pod_receiver'] ?? '',
            'pod_date'     => $summary['pod_date'] ?? '',
            'history'      => $parsedHistory,
        ];
    }

    // ═══════════════════════════════════════════════════════
    // HELPERS
    // ═══════════════════════════════════════════════════════

    /**
     * Flatten hasil ongkir ke format array seragam
     */
    protected function flattenCourierResults(array $results): array
    {
        $services = [];

        foreach ($results as $courierResult) {
            $courierName = $courierResult['name'] ?? '';
            $courierCode = $courierResult['code'] ?? '';

            foreach ($courierResult['costs'] ?? [] as $cost) {
                $costValue = (int) ($cost['cost'] ?? 0);
                $etd       = $cost['etd'] ?? '-';

                $services[] = [
                    'courier_code' => $courierCode,
                    'courier_name' => $courierName,
                    'service'      => $cost['service'] ?? '',
                    'description'  => $cost['description'] ?? '',
                    'cost'         => $costValue,
                    'etd'          => $etd,
                    'label'        => $courierName . ' ' . ($cost['service'] ?? '')
                        . ' — Rp ' . number_format($costValue, 0, ',', '.')
                        . ' (ETD: ' . $etd . ' hari)',
                ];
            }
        }

        return $services;
    }

    /**
     * Mapping nama kurir input → kode Binderbyte
     */
    protected array $courierMap = [
        'jne'           => 'jne',
        'j&t'           => 'jnt',
        'jnt'           => 'jnt',
        'j&t express'   => 'jnt',
        'sicepat'       => 'sicepat',
        'si cepat'      => 'sicepat',
        'tiki'          => 'tiki',
        'pos'           => 'pos',
        'pos indonesia' => 'pos',
        'anteraja'      => 'anteraja',
        'lion'          => 'lion',
        'lion parcel'   => 'lion',
        'ninja'         => 'ninja',
        'ninja xpress'  => 'ninja',
        'sap'           => 'sap',
        'ide'           => 'ide',
        'id express'    => 'ide',
        'wahana'        => 'wahana',
    ];

    public function resolveCourierCode(string $input): ?string
    {
        $lower = strtolower(trim($input));

        // Direct match
        if (isset($this->courierMap[$lower])) {
            return $this->courierMap[$lower];
        }

        // Partial match
        foreach ($this->courierMap as $key => $code) {
            if (str_contains($lower, $key) || str_contains($key, $lower)) {
                return $code;
            }
        }

        // Kata pertama
        $firstWord = explode(' ', $lower)[0];
        return $this->courierMap[$firstWord] ?? null;
    }

    /**
     * Normalisasi nama kota untuk Binderbyte (lowercase, tanpa "kota"/"kabupaten")
     */
    protected function normalizeCityName(string $city): string
    {
        $city = strtolower(trim($city));

        // Hapus prefix kota / kabupaten / kab. / kab (dengan atau tanpa titik/spasi)
        $city = preg_replace('/^(kota|kabupaten|kab\.?)\s+/i', '', $city);

        // Normalisasi spasi ganda
        $city = preg_replace('/\s+/', ' ', $city);

        return trim($city);
    }

    protected function cleanAwb(string $awb): string
    {
        preg_match('/[A-Za-z0-9]{6,}/', $awb, $matches);
        return $matches[0] ?? $awb;
    }

    protected function friendlyError(string $msg): string
    {
        $lower = strtolower($msg);

        if (str_contains($lower, 'not found') || str_contains($lower, 'tidak ditemukan')) {
            return 'Nomor resi tidak ditemukan. Pastikan nomor resi sudah aktif (biasanya 15-30 menit setelah paket diserahkan ke kurir).';
        }

        if (str_contains($lower, 'invalid') || str_contains($lower, 'tidak valid')) {
            return 'Nomor resi atau kurir tidak valid. Periksa kembali data pengiriman.';
        }

        return $msg;
    }

    protected function httpErrorMessage(int $status): string
    {
        return match ($status) {
            400 => 'Parameter tracking tidak lengkap.',
            401 => 'API Key tidak valid. Periksa konfigurasi BINDERBYTE_API_KEY.',
            403 => 'Akses ditolak. Kuota API mungkin sudah habis.',
            404 => 'Nomor resi tidak ditemukan.',
            429 => 'Terlalu banyak permintaan. Coba lagi nanti.',
            500, 502, 503 => 'Server tracking sedang sibuk. Coba lagi beberapa saat.',
            default => 'Gagal mengambil data tracking. HTTP ' . $status,
        };
    }

    public function getSupportedCouriers(): array
    {
        return ['JNE', 'J&T Express', 'SiCepat', 'ID Express', 'POS Indonesia', 'TIKI', 'AnterAja', 'Lion Parcel', 'SAP Express', 'Ninja Xpress', 'Wahana'];
    }
}