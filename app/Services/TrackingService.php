<?php

namespace App\Services;

/**
 * TrackingService — sekarang delegate ke BinderbyteService.
 * File ini dipertahankan agar tidak perlu ubah controller SerahTerimaPembelian.
 */
class TrackingService
{
    protected BinderbyteService $binderbyte;

    public function __construct(BinderbyteService $binderbyte)
    {
        $this->binderbyte = $binderbyte;
    }

    /**
     * Track resi (dengan cache 15 menit)
     */
    public function track(string $awb, string $courier): array
    {
        return $this->binderbyte->track($awb, $courier);
    }

    /**
     * Force refresh (bypass cache)
     */
    public function refresh(string $awb, string $courier): array
    {
        return $this->binderbyte->refresh($awb, $courier);
    }

    /**
     * Daftar kurir yang didukung
     */
    public function getSupportedCouriers(): array
    {
        return $this->binderbyte->getSupportedCouriers();
    }
}