<?php
// app/Services/TiketLaporanService.php

namespace App\Services;

use App\Models\DetailPengunjung;
use App\Models\PemesananTiket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TiketLaporanService
{
    public function getLaporanPendapatan($startDate, $endDate, $groupBy = 'daily')
    {
        $query = PemesananTiket::with(['ticket' => function($q) {
                $q->withTrashed();
            }])
            ->where('status', 'lunas')
            ->whereBetween('tanggal_bayar', [$startDate, $endDate]);

        $pemesanan = $query->get()->filter(function ($item) {
            return $item->ticket !== null;
        });

        $totalPendapatan = $pemesanan->sum('total_harga');
        $jumlahTransaksi = $pemesanan->count();
        $rataTransaksi = $totalPendapatan > 0 && $jumlahTransaksi > 0 ? $totalPendapatan / $jumlahTransaksi : 0;

        $chartData = $this->getPendapatanChartData($startDate, $endDate, $groupBy);

        return [
            'total_pendapatan' => $totalPendapatan,
            'jumlah_transaksi' => $jumlahTransaksi,
            'rata_transaksi' => $rataTransaksi,
            'chart_labels' => $chartData['labels'],
            'chart_data' => $chartData['data'],
        ];
    }

    private function getPendapatanChartData($startDate, $endDate, $groupBy)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $labels = [];
        $data = [];

        switch ($groupBy) {
            case 'daily':
                for ($date = $start->copy(); $date <= $end; $date->addDay()) {
                    $labels[] = $date->format('d M Y');
                    $data[] = PemesananTiket::where('status', 'lunas')
                        ->whereDate('tanggal_bayar', $date)
                        ->whereHas('ticket', function($q) {
                            $q->withTrashed();
                        })
                        ->sum('total_harga');
                }
                break;

            case 'monthly':
                for ($date = $start->copy()->startOfMonth(); $date <= $end; $date->addMonth()) {
                    $labels[] = $date->format('M Y');
                    $data[] = PemesananTiket::where('status', 'lunas')
                        ->whereYear('tanggal_bayar', $date->year)
                        ->whereMonth('tanggal_bayar', $date->month)
                        ->whereHas('ticket', function($q) {
                            $q->withTrashed();
                        })
                        ->sum('total_harga');
                }
                break;

            case 'quarterly':
                for ($date = $start->copy(); $date <= $end; $date->addMonths(3)) {
                    $quarter = ceil($date->month / 3);
                    $labels[] = "Q{$quarter} {$date->year}";
                    $data[] = PemesananTiket::where('status', 'lunas')
                        ->whereYear('tanggal_bayar', $date->year)
                        ->whereBetween(DB::raw('QUARTER(tanggal_bayar)'), [$quarter, $quarter])
                        ->whereHas('ticket', function($q) {
                            $q->withTrashed();
                        })
                        ->sum('total_harga');
                }
                break;

            case 'yearly':
                for ($date = $start->copy()->startOfYear(); $date <= $end; $date->addYear()) {
                    $labels[] = $date->format('Y');
                    $data[] = PemesananTiket::where('status', 'lunas')
                        ->whereYear('tanggal_bayar', $date->year)
                        ->whereHas('ticket', function($q) {
                            $q->withTrashed();
                        })
                        ->sum('total_harga');
                }
                break;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function getLaporanPenjualan($startDate, $endDate)
    {
        return PemesananTiket::where('status', 'lunas')
            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->with(['ticket' => function($q) {
                $q->withTrashed();
            }])
            ->get()
            ->filter(function ($item) {
                return $item->ticket !== null;
            })
            ->groupBy('ticket_id')
            ->map(function ($group) {
                $ticket = $group->first()->ticket;
                
                if (!$ticket) {
                    return null;
                }

                return [
                    'ticket_id' => $ticket->id,
                    'jenis_tiket' => $ticket->nama_tiket ?? 'Tiket Dihapus',
                    'sub_jenis' => $ticket->sub_jenis ?? '-',
                    'harga' => $ticket->harga ?? 0,
                    'jumlah_terjual' => $group->sum('jumlah_tiket'),
                    'total_pendapatan' => $group->sum('total_harga'),
                ];
            })
            ->filter()
            ->values()
            ->toArray();
    }

    public function getLaporanPengunjung($startDate, $endDate)
    {
        $pemesananIds = PemesananTiket::where('status', 'lunas')
            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->whereHas('ticket', function($q) {
                $q->withTrashed();
            })
            ->pluck('id');

        $totalPengunjung = DetailPengunjung::whereIn('pemesanan_tiket_id', $pemesananIds)->count();

        $pengunjungByTiket = DetailPengunjung::whereIn('pemesanan_tiket_id', $pemesananIds)
            ->with(['pemesananTiket' => function($q) {
                $q->with(['ticket' => function($t) {
                    $t->withTrashed();
                }]);
            }])
            ->get()
            ->filter(function ($item) {
                return $item->pemesananTiket && $item->pemesananTiket->ticket !== null;
            })
            ->groupBy(function ($item) {
                $ticket = $item->pemesananTiket->ticket;
                return $ticket->jenis_tiket ?? 'Reguler';
            })
            ->map(function ($group) {
                return $group->count();
            });

        return [
            'total_pengunjung' => $totalPengunjung,
            'pengunjung_by_tiket' => $pengunjungByTiket,
        ];
    }

    public function getLaporanTransaksi($startDate, $endDate, $status = null, $metode = null)
    {
        $query = PemesananTiket::whereBetween('tanggal_pemesanan', [$startDate, $endDate])
            ->with(['user', 'ticket' => function($q) {
                $q->withTrashed();
            }]);

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($metode && $metode !== 'all') {
            $query->where('metode_pembayaran', $metode);
        }

        return $query->orderBy('tanggal_pemesanan', 'desc')->paginate(20);
    }

    public function getLaporanMetodePembayaran($startDate, $endDate)
    {
        $data = PemesananTiket::where('status', 'lunas')
            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->whereNotNull('metode_pembayaran')
            ->whereHas('ticket', function($q) {
                $q->withTrashed();
            })
            ->select('metode_pembayaran', DB::raw('COUNT(*) as jumlah_transaksi'), DB::raw('SUM(total_harga) as total_pendapatan'))
            ->groupBy('metode_pembayaran')
            ->get();

        $totalSemua = $data->sum('jumlah_transaksi');

        foreach ($data as $item) {
            $item->persentase = $totalSemua > 0 ? ($item->jumlah_transaksi / $totalSemua) * 100 : 0;
        }

        return $data;
    }

    public function getLaporanPerTiket($startDate, $endDate)
    {
        return PemesananTiket::where('status', 'lunas')
            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->with(['ticket' => function($q) {
                $q->withTrashed();
            }])
            ->get()
            ->filter(function ($item) {
                return $item->ticket !== null;
            })
            ->groupBy('ticket_id')
            ->map(function ($group) {
                $ticket = $group->first()->ticket;
                
                if (!$ticket) {
                    return null;
                }

                return [
                    'ticket_id' => $ticket->id,
                    'nama_tiket' => $ticket->nama_tiket ?? 'Tiket Dihapus',
                    'jenis_tiket' => $ticket->jenis_tiket ?? '-',
                    'sub_jenis' => $ticket->sub_jenis ?? '-',
                    'kategori_pengunjung' => $ticket->kategori_pengunjung ?? '-',
                    'harga' => $ticket->harga ?? 0,
                    'total_terjual' => $group->sum('jumlah_tiket'),
                    'total_pendapatan' => $group->sum('total_harga'),
                    'rata_rata' => $group->count() > 0 ? $group->sum('total_harga') / $group->count() : 0,
                ];
            })
            ->filter()
            ->values()
            ->toArray();
    }

    public function getSummaryLaporan($startDate, $endDate)
    {
        $pemesanan = PemesananTiket::where('status', 'lunas')
            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->with(['ticket' => function($q) {
                $q->withTrashed();
            }])
            ->get()
            ->filter(function ($item) {
                return $item->ticket !== null;
            });

        $totalPendapatan = $pemesanan->sum('total_harga');
        $totalTiket = $pemesanan->sum('jumlah_tiket');
        $totalTransaksi = $pemesanan->count();

        return [
            'total_pendapatan' => $totalPendapatan,
            'total_tiket' => $totalTiket,
            'total_transaksi' => $totalTransaksi,
            'rata_rata_per_transaksi' => $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0,
            'rata_rata_per_tiket' => $totalTiket > 0 ? $totalPendapatan / $totalTiket : 0,
        ];
    }
}