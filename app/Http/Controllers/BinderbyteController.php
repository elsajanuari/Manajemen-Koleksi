<?php

namespace App\Http\Controllers;

use App\Services\BinderbyteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BinderbyteController extends Controller
{
    public function __construct(protected BinderbyteService $binderbyte) {}

    // ── GET /api/binderbyte/provinces ─────────────────────────────────
    public function provinces(): JsonResponse
    {
        $provinces = $this->binderbyte->getProvinces();

        return response()->json($provinces)
            ->header('Cache-Control', 'public, max-age=2592000'); // 30 hari
    }

    // ── GET /api/binderbyte/cities?province_id=xx ─────────────────────
    public function cities(Request $request): JsonResponse
    {
        $provinceId = trim($request->query('province_id', ''));

        if (!$provinceId) {
            return response()->json([]);
        }

        $cities = $this->binderbyte->getCitiesByProvince($provinceId);

        return response()->json($cities)
            ->header('Cache-Control', 'public, max-age=2592000');
    }

    // ── POST /api/binderbyte/cost ─────────────────────────────────────
    // Body: { city_name: string, weight_gram: int }
    public function cost(Request $request): JsonResponse
    {
        $request->validate([
            'city_name'   => ['required', 'string', 'max:100'],
            'weight_gram' => ['required', 'integer', 'min:1'],
        ]);

        $cityName   = trim($request->input('city_name'));
        $weightGram = (int) $request->input('weight_gram');

        // ← TAMBAH INI SEMENTARA untuk debug
        \Log::info('Binderbyte cost request', [
            'city_name_raw'        => $request->input('city_name'),
            'city_name_normalized' => $cityName,
            'weight_gram'          => $weightGram,
        ]);

        if ($this->binderbyte->isSameCity($cityName)) {
            return response()->json(['is_free' => true, 'services' => []]);
        }

        $services = $this->binderbyte->calculateAllCouriers($cityName, $weightGram);

        // ← TAMBAH INI SEMENTARA untuk debug
        \Log::info('Binderbyte cost result', [
            'city_name' => $cityName,
            'services_count' => count($services),
            'services' => $services,
        ]);

        return response()->json([
            'is_free'  => false,
            'services' => $services,
        ]);
    }
}