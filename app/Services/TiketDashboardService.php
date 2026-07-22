<?php
// app/Services/TiketDashboardService.php

namespace App\Services;

use App\Models\DetailPengunjung;
use App\Models\PemesananTiket;
use App\Models\TicketQuota;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TiketDashboardService
{
    public function getKpiData(): array
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth();

        // Hanya ambil status 'lunas' untuk pendapatan (status refund tidak dihitung)
        $totalPendapatan = PemesananTiket::where('status', 'lunas')->sum('total_harga');

        $pendapatanBulanIni = PemesananTiket::where('status', 'lunas')
            ->where('tanggal_bayar', '>=', $startOfMonth)
            ->sum('total_harga');

        $pendapatanBulanLalu = PemesananTiket::where('status', 'lunas')
            ->whereYear('tanggal_bayar', $lastMonth->year)
            ->whereMonth('tanggal_bayar', $lastMonth->month)
            ->sum('total_harga');

        $pendapatanDiff = $pendapatanBulanIni - $pendapatanBulanLalu;
        $pertumbuhanPendapatan = $pendapatanBulanLalu > 0
            ? ($pendapatanDiff / $pendapatanBulanLalu) * 100
            : ($pendapatanBulanIni > 0 ? 100 : 0);

        $tiketTerjualBulanIni = DetailPengunjung::whereHas('pemesananTiket', function ($q) use ($startOfMonth) {
            $q->where('status', 'lunas')
              ->where('tanggal_bayar', '>=', $startOfMonth);
        })->count();

        $tiketTerjualBulanLalu = DetailPengunjung::whereHas('pemesananTiket', function ($q) use ($lastMonth) {
            $q->where('status', 'lunas')
              ->whereYear('tanggal_bayar', $lastMonth->year)
              ->whereMonth('tanggal_bayar', $lastMonth->month);
        })->count();

        $tiketDiff = $tiketTerjualBulanIni - $tiketTerjualBulanLalu;
        $pertumbuhanPengunjung = $tiketTerjualBulanLalu > 0
            ? ($tiketDiff / $tiketTerjualBulanLalu) * 100
            : ($tiketTerjualBulanIni > 0 ? 100 : 0);

        $totalPengunjung = DetailPengunjung::whereNotNull('tiket_terpakai_at')->count();
        $pengunjungHariIni = DetailPengunjung::whereDate('tiket_terpakai_at', $today)->count();

        $tiketTerlaris = PemesananTiket::where('status', 'lunas')
            ->with('ticket')
            ->select('ticket_id', DB::raw('SUM(jumlah_tiket) as total_terjual'))
            ->groupBy('ticket_id')
            ->orderBy('total_terjual', 'desc')
            ->first();

        return [
            'total_pendapatan' => $totalPendapatan,
            'pendapatan_bulan_ini' => $pendapatanBulanIni,
            'tiket_terjual_bulan_ini' => $tiketTerjualBulanIni,
            'total_pengunjung' => $totalPengunjung,
            'pengunjung_hari_ini' => $pengunjungHariIni,
            'tiket_terlaris' => $tiketTerlaris?->ticket->nama_tiket ?? '-',
            'pertumbuhan_pendapatan' => round($pertumbuhanPendapatan, 2),
            'pendapatan_direction' => $pendapatanDiff >= 0 ? 'up' : 'down',
            'pertumbuhan_pengunjung' => round($pertumbuhanPengunjung, 2),
            'tiket_direction' => $tiketDiff >= 0 ? 'up' : 'down',
        ];
    }

    public function getCombinedChart(string $periode = 'bulanan', int $offset = 0): array
    {
        $labels = [];
        $pendapatanData = [];
        $refundData = [];
        $title = '';
        $totalPendapatan = 0;
        $totalRefund = 0;

        switch ($periode) {
            case 'mingguan':
                $startDate = Carbon::now()->subDays($offset * 7)->startOfWeek();
                for ($i = 0; $i <= 6; $i++) {
                    $date = $startDate->copy()->addDays($i);
                    $labels[] = $date->format('d M');

                    $pendapatan = PemesananTiket::where('status', 'lunas')
                        ->whereDate('tanggal_bayar', $date)
                        ->sum('total_harga');
                    $pendapatanData[] = $pendapatan;
                    $totalPendapatan += $pendapatan;

                    $refund = PemesananTiket::where('status', 'pengembalian_berhasil')
                        ->whereDate('refund_completed_at', $date)
                        ->sum('total_harga');
                    $refundData[] = -$refund;
                    $totalRefund += $refund;
                }
                $title = 'Minggu ke-' . ($offset + 1) . ' dari sekarang';
                break;

            case 'tahunan':
                $startDate = Carbon::now()->subYears($offset)->startOfYear();
                for ($i = 0; $i <= 11; $i++) {
                    $date = $startDate->copy()->addMonths($i);
                    $labels[] = $date->format('M Y');

                    $pendapatan = PemesananTiket::where('status', 'lunas')
                        ->whereYear('tanggal_bayar', $date->year)
                        ->whereMonth('tanggal_bayar', $date->month)
                        ->sum('total_harga');
                    $pendapatanData[] = $pendapatan;
                    $totalPendapatan += $pendapatan;

                    $refund = PemesananTiket::where('status', 'pengembalian_berhasil')
                        ->whereYear('refund_completed_at', $date->year)
                        ->whereMonth('refund_completed_at', $date->month)
                        ->sum('total_harga');
                    $refundData[] = -$refund;
                    $totalRefund += $refund;
                }
                $title = 'Tahun ' . Carbon::now()->subYears($offset)->year;
                break;

            default: // bulanan
                $startDate = Carbon::now()->subMonths($offset)->startOfMonth();
                $daysInMonth = $startDate->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $date = $startDate->copy()->setDay($i);
                    $labels[] = $date->format('d M');

                    $pendapatan = PemesananTiket::where('status', 'lunas')
                        ->whereDate('tanggal_bayar', $date)
                        ->sum('total_harga');
                    $pendapatanData[] = $pendapatan;
                    $totalPendapatan += $pendapatan;

                    $refund = PemesananTiket::where('status', 'pengembalian_berhasil')
                        ->whereDate('refund_completed_at', $date)
                        ->sum('total_harga');
                    $refundData[] = -$refund;
                    $totalRefund += $refund;
                }
                $title = $startDate->locale('id')->translatedFormat('F Y');
                break;
        }

        return [
            'labels' => $labels,
            'pendapatan' => $pendapatanData,
            'refund' => $refundData,
            'title' => $title,
            'offset' => $offset,
            'periode' => $periode,
            'total_pendapatan' => $totalPendapatan,
            'total_refund' => $totalRefund,
        ];
    }

    public function getPenjualanChart(): array
    {
        $jenisTiket = PemesananTiket::where('status', 'lunas')
            ->with('ticket')
            ->get()
            ->groupBy(function ($item) {
                $ticket = $item->ticket;
                if (!$ticket) return 'Reguler';

                $jenis = strtolower((string) $ticket->jenis_tiket);
                $sub = strtolower((string) $ticket->sub_jenis ?: '');

                if ($jenis === 'event') {
                    return match ($sub) {
                        'pameran' => 'Pameran',
                        'sunday painting' => 'Sunday Painting',
                        'workshop' => 'Workshop',
                        default => 'Lainnya',
                    };
                }

                return 'Reguler';
            })
            ->map(fn ($group) => $group->sum('jumlah_tiket'));

        $kategoriPengunjung = DetailPengunjung::whereHas('pemesananTiket', function ($q) {
            $q->where('status', 'lunas');
        })->get()
            ->groupBy('tipe_pengunjung')
            ->map(fn ($group) => $group->count());

        return [
            'jenis_tiket' => [
                'labels' => $jenisTiket->keys()->toArray(),
                'data' => $jenisTiket->values()->toArray(),
            ],
            'kategori' => [
                'labels' => $kategoriPengunjung->keys()->toArray(),
                'data' => $kategoriPengunjung->values()->toArray(),
            ],
        ];
    }

    public function getPengunjungChart(int $offset = 0): array
    {
        $data = [];
        $labels = [];
        $startDate = Carbon::now()->subDays($offset * 7);

        for ($i = 6; $i >= 0; $i--) {
            $date = $startDate->copy()->subDays($i);
            $labels[] = $date->format('d M');
            $data[] = DetailPengunjung::whereDate('tiket_terpakai_at', $date)->count();
        }

        $title = 'Periode: ' . $startDate->copy()->subDays(6)->format('d M') . ' - ' . $startDate->format('d M Y');

        return [
            'labels' => $labels,
            'data' => $data,
            'title' => $title,
            'offset' => $offset,
        ];
    }

    public function getStatistikCepat(): array
    {
        $hariTerbanyak = DetailPengunjung::select(DB::raw('DATE(tiket_terpakai_at) as tanggal'), DB::raw('count(*) as total'))
            ->whereNotNull('tiket_terpakai_at')
            ->groupBy('tanggal')
            ->orderBy('total', 'desc')
            ->first();

        $bulanTertinggi = PemesananTiket::where('status', 'lunas')
            ->select(DB::raw('YEAR(tanggal_bayar) as tahun'), DB::raw('MONTH(tanggal_bayar) as bulan'), DB::raw('SUM(total_harga) as total'))
            ->groupBy('tahun', 'bulan')
            ->orderBy('total', 'desc')
            ->first();

        $transaksiSukses = PemesananTiket::where('status', 'lunas')->count();
        $transaksiGagal = PemesananTiket::where('status', 'dibatalkan')->count();
        $transaksiPending = PemesananTiket::where('status', 'menunggu_pembayaran')->count();
        $transaksiRefund = PemesananTiket::where('status', 'pengembalian_berhasil')->count();
        $transaksiProsesRefund = PemesananTiket::where('status', 'proses_pembatalan')->count();

        return [
            'hari_terbanyak' => $hariTerbanyak ? Carbon::parse($hariTerbanyak->tanggal)->locale('id')->translatedFormat('d F Y') . ' (' . $hariTerbanyak->total . ')' : '-',
            'bulan_tertinggi' => $bulanTertinggi ? Carbon::create()->month($bulanTertinggi->bulan)->locale('id')->translatedFormat('F') . ' ' . $bulanTertinggi->tahun . ' (Rp ' . number_format($bulanTertinggi->total, 0, ',', '.') . ')' : '-',
            'transaksi_sukses' => $transaksiSukses,
            'transaksi_gagal' => $transaksiGagal,
            'transaksi_pending' => $transaksiPending,
            'transaksi_refund' => $transaksiRefund,
            'transaksi_proses_refund' => $transaksiProsesRefund,
        ];
    }

    /**
     * Statistik refund: sudah selesai vs masih menunggu/diproses.
     * Dipakai untuk section "Perlu Perhatian" pada dashboard.
     */
    public function getRefundStatistics(): array
    {
        $totalRefundSelesai = PemesananTiket::where('status', 'pengembalian_berhasil')
            ->sum('total_harga');

        $jumlahRefundSelesai = PemesananTiket::where('status', 'pengembalian_berhasil')
            ->count();

        $totalRefundProses = PemesananTiket::where('status', 'proses_pembatalan')
            ->sum('total_harga');

        $jumlahRefundProses = PemesananTiket::where('status', 'proses_pembatalan')
            ->count();

        return [
            'total_refund_selesai' => $totalRefundSelesai,
            'jumlah_refund_selesai' => $jumlahRefundSelesai,
            'total_refund_proses' => $totalRefundProses,
            'jumlah_refund_proses' => $jumlahRefundProses,
        ];
    }

    /**
     * Status scan khusus untuk kunjungan HARI INI saja (bukan sepanjang waktu).
     * Beda scope dengan getScanStatusData() yang bersifat akumulatif —
     * ini untuk kebutuhan operasional harian: "hari ini masih ada berapa
     * pengunjung yang belum check-in?"
     */
    public function getScanStatusHariIni(): array
    {
        $today = Carbon::today();

        $totalHariIni = DetailPengunjung::whereHas('pemesananTiket', function ($q) use ($today) {
            $q->where('status', 'lunas')
                ->whereDate('tanggal_pemesanan', $today);
        })->count();

        $sudahScanHariIni = DetailPengunjung::whereHas('pemesananTiket', function ($q) use ($today) {
            $q->where('status', 'lunas')
                ->whereDate('tanggal_pemesanan', $today);
        })->whereNotNull('tiket_terpakai_at')->count();

        return [
            'total' => $totalHariIni,
            'sudah_scan' => $sudahScanHariIni,
            'belum_scan' => $totalHariIni - $sudahScanHariIni,
        ];
    }

    public function getVisitorByTicketCategory(): array
    {
        $data = DetailPengunjung::whereHas('pemesananTiket', function ($q) {
            $q->where('status', 'lunas');
        })
        ->with('pemesananTiket.ticket')
        ->get()
        ->groupBy(function ($item) {
            $ticket = $item->pemesananTiket->ticket ?? null;
            if (!$ticket) return 'Reguler';

            $jenis = strtolower((string) $ticket->jenis_tiket);
            $sub = strtolower((string) $ticket->sub_jenis ?: '');

            if ($jenis === 'event') {
                return match ($sub) {
                    'pameran' => 'Pameran',
                    'sunday painting' => 'Sunday Painting',
                    'workshop' => 'Workshop',
                    default => 'Lainnya',
                };
            }

            return 'Reguler';
        })
        ->map(fn ($group) => $group->count())
        ->sort()
        ->reverse();

        return [
            'labels' => $data->keys()->toArray(),
            'data' => $data->values()->toArray(),
        ];
    }

    /**
     * Kapasitas kunjungan hari ini: berapa persen kuota sudah terisi.
     * Penting untuk museum fisik — beda dengan "pendapatan"/"pengunjung terscan",
     * ini menjawab "hari ini masih ada slot kosong atau sudah penuh?".
     */
    public function getKapasitasHariIni(): array
    {
        $today = Carbon::today();

        $quotas = TicketQuota::whereDate('tanggal', $today)
            ->whereHas('ticket', function ($q) {
                $q->where('status', true);
            })
            ->get();

        $totalTerjual = $quotas->sum('kuota_terjual');
        $totalSisa = $quotas->sum('kuota_sisa');
        $totalKuota = $totalTerjual + $totalSisa;

        $persentaseTerisi = $totalKuota > 0 ? ($totalTerjual / $totalKuota) * 100 : 0;

        return [
            'ada_jadwal' => $quotas->isNotEmpty(),
            'total_kuota' => $totalKuota,
            'terjual' => $totalTerjual,
            'sisa' => $totalSisa,
            'persentase_terisi' => round($persentaseTerisi, 1),
        ];
    }

    /**
     * Pemesanan yang belum dibayar dan berisiko/sudah dibatalkan otomatis
     * oleh sistem (lihat PemesananTiket::expireJikaKedaluwarsa()).
     * Membantu pengelola follow-up manual (mis. WA reminder) sebelum
     * sistem auto-cancel pemesanan yang kunjungannya besok.
     */
    public function getPemesananBerisikoExpired(): array
    {
        $today = Carbon::today('Asia/Jakarta');
        $besok = $today->copy()->addDay();

        $sudahKedaluwarsa = PemesananTiket::where('status', 'menunggu_pembayaran')
            ->where('tanggal_pemesanan', '<', $today)
            ->count();

        $kunjunganHariIni = PemesananTiket::where('status', 'menunggu_pembayaran')
            ->whereDate('tanggal_pemesanan', $today)
            ->count();

        $kunjunganBesok = PemesananTiket::where('status', 'menunggu_pembayaran')
            ->whereDate('tanggal_pemesanan', $besok)
            ->count();

        return [
            'sudah_kedaluwarsa' => $sudahKedaluwarsa,
            'kunjungan_hari_ini' => $kunjunganHariIni,
            'kunjungan_besok' => $kunjunganBesok,
            'total_berisiko' => $sudahKedaluwarsa + $kunjunganHariIni + $kunjunganBesok,
        ];
    }

    public function getScanStatusData(): array
    {
        $totalTiket = DetailPengunjung::whereHas('pemesananTiket', function ($q) {
            $q->where('status', 'lunas');
        })->count();

        $sudahDiScan = DetailPengunjung::whereHas('pemesananTiket', function ($q) {
            $q->where('status', 'lunas');
        })->whereNotNull('tiket_terpakai_at')->count();

        $belumDiScan = $totalTiket - $sudahDiScan;

        $persentaseScan = $totalTiket > 0 ? ($sudahDiScan / $totalTiket) * 100 : 0;

        return [
            'total_tiket' => $totalTiket,
            'sudah_di_scan' => $sudahDiScan,
            'belum_di_scan' => $belumDiScan,
            'persentase_scan' => round($persentaseScan, 2),
        ];
    }
}