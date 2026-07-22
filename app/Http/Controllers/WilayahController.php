<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WilayahController extends Controller
{
    private string $base = 'https://emsifa.github.io/api-wilayah-indonesia/api';

    public function provinces()
    {
        $data = Cache::remember('wilayah_provinces', 86400, fn() =>
            Http::get("{$this->base}/provinces.json")->json()
        );
        return response()->json($data);
    }

    public function regencies(string $provinceId)
    {
        $data = Cache::remember("wilayah_regencies_{$provinceId}", 86400, fn() =>
            Http::get("{$this->base}/regencies/{$provinceId}.json")->json()
        );
        return response()->json($data);
    }

    public function districts(string $cityId)
    {
        $data = Cache::remember("wilayah_districts_{$cityId}", 86400, fn() =>
            Http::get("{$this->base}/districts/{$cityId}.json")->json()
        );
        return response()->json($data);
    }

    public function villages(string $districtId)
    {
        $data = Cache::remember("wilayah_villages_{$districtId}", 86400, fn() =>
            Http::get("{$this->base}/villages/{$districtId}.json")->json()
        );
        return response()->json($data);
    }
}