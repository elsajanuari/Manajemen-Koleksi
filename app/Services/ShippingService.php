<?php

namespace App\Services;

use App\Models\Pembelian;
use App\Models\ShippingZone;
use App\Models\ShippingZoneProvince;

class ShippingService
{
    // ── Daftar kabupaten/kota yang termasuk Purwakarta (Zona 1) ──
    // Zona 1 dideteksi dari kota_kabupaten = "Purwakarta"
    // dan provinsi = "Jawa Barat"
    const PURWAKARTA_KEYWORDS = ['purwakarta'];

    // ── Threshold harga wajib pengelola ─────────────────────────
    const MANAGER_ONLY_PRICE = 10_000_000;

    // ─────────────────────────────────────────────────────────────
    // Tentukan zona berdasarkan provinsi + kota_kabupaten
    // Return: ShippingZone
    // ─────────────────────────────────────────────────────────────
    public function resolveZone(string $provinsi, string $kotaKabupaten): ShippingZone
    {
        // Cek apakah termasuk Purwakarta (Zona 1)
        $kotaNormalized = strtolower(trim($kotaKabupaten));
        foreach (self::PURWAKARTA_KEYWORDS as $keyword) {
            if (str_contains($kotaNormalized, $keyword)) {
                return ShippingZone::where('zone_name', 'Zona 1')->first();
            }
        }

        // Cek mapping provinsi → zona
        $zoneProvince = ShippingZoneProvince::where('province_name', $provinsi)->first();

        if ($zoneProvince) {
            return $zoneProvince->zone;
        }

        // Fallback: Zona 4 jika tidak ditemukan
        return ShippingZone::where('zone_name', 'Zona 4')->first();
    }

    // ─────────────────────────────────────────────────────────────
    // Cek apakah koleksi WAJIB dikirim oleh pengelola
    // (harga >= 10 juta)
    // ─────────────────────────────────────────────────────────────
    public function isManagerOnly(float $hargaBeli): bool
    {
        return $hargaBeli >= self::MANAGER_ONLY_PRICE;
    }

    // ─────────────────────────────────────────────────────────────
    // Hitung ongkir berdasarkan zona & metode
    // Return: array [shipping_cost, shipping_method_type, zone]
    // ─────────────────────────────────────────────────────────────
    public function calculate(
        ShippingZone $zone,
        string $methodType, // 'courier' | 'manager'
        ?float $overrideAmount = null
    ): float {
        // Zona 1 (Purwakarta) → selalu gratis
        if ($zone->is_free) {
            return 0;
        }

        // Jika ada override dari pengelola, pakai itu
        if ($overrideAmount !== null) {
            return $overrideAmount;
        }

        // Default: flat rate zona (untuk metode pengelola)
        // Untuk kurir, pengelola wajib input manual (override)
        return (float) $zone->manager_rate;
    }

    // ─────────────────────────────────────────────────────────────
    // Summary info zona untuk ditampilkan ke pengelola
    // ─────────────────────────────────────────────────────────────
    public function getZoneSummary(Pembelian $pembelian): array
    {
        $zone = $this->resolveZone(
            $pembelian->provinsi,
            $pembelian->kota_kabupaten
        );

        $isManagerOnly  = $this->isManagerOnly($pembelian->harga_beli);
        $isFree         = $zone->is_free;

        return [
            'zone'            => $zone,
            'is_free'         => $isFree,
            'is_manager_only' => $isManagerOnly,
            'default_rate'    => $zone->manager_rate,
            'formatted_rate'  => $zone->formatted_rate,
            'warning'         => $isManagerOnly
                ? 'Harga koleksi ≥ Rp 10 juta. Disarankan dikirim oleh pengelola, bukan kurir.'
                : null,
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // Summary info zona berdasarkan provinsi + kota (untuk Penyewaan)
    // ─────────────────────────────────────────────────────────────
    public function getZoneSummaryByProvince(?string $provinsi, ?string $kotaKabupaten): ?array
    {
        if (!$provinsi && !$kotaKabupaten) {
            return null;
        }

        try {
            $zone = $this->resolveZone($provinsi ?? '', $kotaKabupaten ?? '');

            if (!$zone) {
                return null;
            }

            return [
                'zone'           => $zone,
                'is_free'        => (bool) $zone->is_free,
                'default_rate'   => $zone->manager_rate,
                'formatted_rate' => $zone->formatted_rate,
                'warning'        => null,
            ];
        } catch (\Throwable $e) {
            \Log::warning('getZoneSummaryByProvince gagal: ' . $e->getMessage());
            return null;
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Data zona untuk dikirim ke JavaScript (preview ongkir di view)
    // ─────────────────────────────────────────────────────────────
    public function getZonaDataForJs(): array
    {
        return ShippingZone::with('provinces')->get()->map(function ($zone) {
            return [
                'id'           => $zone->id,
                'zone_name'    => $zone->zone_name,
                'description'  => $zone->description,
                'manager_rate' => (int) $zone->manager_rate,
                'is_free'      => (bool) $zone->is_free,
                'provinces'    => $zone->provinces->map(fn($p) => [
                    'province_name' => $p->province_name,
                ])->values()->toArray(),
            ];
        })->values()->toArray();
    }
}