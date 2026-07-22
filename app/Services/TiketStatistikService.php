<?php

namespace App\Services;

use App\Models\DetailPengunjung;
use App\Models\PemesananTiket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TiketStatistikService
{
    public function getPengunjungBaruVsLama(): array
    {
        // Pengunjung baru vs lama berdasarkan email unik
        $semuaEmail = DetailPengunjung::whereNotNull('email')
            ->whereHas('pemesananTiket', function($q) {
                $q->where('status', 'lunas');
            })
            ->select('email', DB::raw('COUNT(*) as total_kunjungan'))
            ->groupBy('email')
            ->get();

        $pengunjungBaru = $semuaEmail->filter(fn($item) => $item->total_kunjungan === 1)->count();
        $pengunjungLama = $semuaEmail->filter(fn($item) => $item->total_kunjungan > 1)->count();

        return [
            'baru' => $pengunjungBaru,
            'lama' => $pengunjungLama,
        ];
    }

    public function getPengunjungByKota(): array
    {
        $kotaData = DetailPengunjung::whereNotNull('alamat')
            ->whereHas('pemesananTiket', function($q) {
                $q->where('status', 'lunas');
            })
            ->get()
            ->groupBy(function($item) {
                // Ekstrak kota dari alamat (sederhana)
                $alamat = $item->alamat ?? $item->alamat_penanggung_jawab ?? '';
                $parts = explode(',', $alamat);
                return trim(end($parts)) ?: 'Lainnya';
            })
            ->map(fn($group) => $group->count())
            ->sortDesc()
            ->take(10);

        return [
            'labels' => $kotaData->keys()->toArray(),
            'data' => $kotaData->values()->toArray(),
        ];
    }

    public function getPengunjungByJenisTiket(): array
    {
        $data = PemesananTiket::where('status', 'lunas')
            ->with('ticket')
            ->get()
            ->groupBy(fn($item) => $item->ticket->jenis_tiket ?? 'Reguler')
            ->map(fn($group) => $group->sum('jumlah_tiket'));

        return [
            'labels' => $data->keys()->toArray(),
            'data' => $data->values()->toArray(),
        ];
    }

    public function getTopPengunjung(int $limit = 10): array
    {
        return DetailPengunjung::whereHas('pemesananTiket', function($q) {
            $q->where('status', 'lunas');
        })
        ->select('email', 'nama_lengkap', DB::raw('COUNT(*) as total_kunjungan'), DB::raw('SUM(pemesanan_tikets.total_harga) as total_pembelian'))
        ->join('pemesanan_tikets', 'detail_pengunjungs.pemesanan_tiket_id', '=', 'pemesanan_tikets.id')
        ->groupBy('email', 'nama_lengkap')
        ->orderBy('total_kunjungan', 'desc')
        ->limit($limit)
        ->get()
        ->toArray();
    }
}