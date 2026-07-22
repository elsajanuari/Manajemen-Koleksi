<?php

namespace App\Http\Controllers;

use App\Services\RajaOngkirService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RajaOngkirController extends Controller
{
    public function __construct(protected RajaOngkirService $rajaOngkir) {}

    // ── GET /api/rajaongkir/provinces ─────────────────────────────────────
    public function provinces(): JsonResponse
    {
        $provinces = $this->rajaOngkir->getProvinces();

        return response()->json($provinces)
            ->header('Cache-Control', 'public, max-age=2592000');
    }

    // ── GET /api/rajaongkir/cities?province_id=xx ─────────────────────────
    public function cities(Request $request): JsonResponse
    {
        $provinceId = (int) $request->query('province_id', 0);

        if (! $provinceId) {
            return response()->json([]);
        }

        $cities = $this->rajaOngkir->getCitiesByProvince($provinceId);

        return response()->json($cities)
            ->header('Cache-Control', 'public, max-age=2592000');
    }

    // ── POST /api/rajaongkir/cost ─────────────────────────────────────────
    // Body: { destination_city_id: int, weight_gram: int }
    public function cost(Request $request): JsonResponse
    {
        $request->validate([
            'destination_city_id' => ['required', 'integer', 'min:1'],
            'weight_gram'         => ['required', 'integer', 'min:1'],
        ]);

        $destinationCityId = (int) $request->input('destination_city_id');
        $weightGram        = (int) $request->input('weight_gram');

        if ($destinationCityId === $this->rajaOngkir->getOriginCityId()) {
            return response()->json(['is_free' => true, 'services' => []]);
        }

        $services = $this->rajaOngkir->calculateAllCouriers($destinationCityId, $weightGram);

        return response()->json([
            'is_free'  => false,
            'services' => $services,
        ]);
    }

    // ── GET /api/rajaongkir/find-city?city_name=bandung&province_name=Jawa+Barat
    // Lookup city_id RajaOngkir dari nama kota + nama provinsi
    public function findCity(Request $request): JsonResponse
    {
        $cityName     = trim($request->query('city_name', ''));
        $provinceName = trim($request->query('province_name', ''));

        if (! $cityName || ! $provinceName) {
            return response()->json(['city_id' => null]);
        }

        // Cari province_id RajaOngkir dari nama provinsi
        $provinces  = $this->rajaOngkir->getProvinces();
        $provinceId = null;

        $provinceNameLower = strtolower($provinceName);
        foreach ($provinces as $p) {
            if (strtolower($p['province']) === $provinceNameLower) {
                $provinceId = (int) $p['province_id'];
                break;
            }
        }

        if (! $provinceId) {
            return response()->json(['city_id' => null]);
        }

        $cityId = $this->rajaOngkir->findCityId($cityName, $provinceId);

        return response()->json(['city_id' => $cityId]);
    }
}