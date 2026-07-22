<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\TiketDashboardService;
use Illuminate\Http\Request;

class TiketDashboardController extends Controller
{
    protected $tiketDashboardService;

    public function __construct(TiketDashboardService $tiketDashboardService)
    {
        $this->tiketDashboardService = $tiketDashboardService;
    }

    public function index(Request $request)
    {
        $periode = $request->get('periode', 'bulanan');

        // Filter untuk grafik pendapatan
        $pendapatanOffset = (int) $request->get('pendapatan_offset', 0);
        $pendapatanPeriode = $request->get('pendapatan_periode', $periode);

        // Filter untuk grafik pengunjung
        $pengunjungOffset = (int) $request->get('pengunjung_offset', 0);

        // ===== Layer 1: Ringkasan utama =====
        $kpi = $this->tiketDashboardService->getKpiData();
        $refundStatistics = $this->tiketDashboardService->getRefundStatistics();

        // ===== Layer 2: Perlu perhatian (actionable) =====
        $scanStatus = $this->tiketDashboardService->getScanStatusData();
        $scanStatusHariIni = $this->tiketDashboardService->getScanStatusHariIni();
        $kapasitasHariIni = $this->tiketDashboardService->getKapasitasHariIni();
        $pemesananBerisiko = $this->tiketDashboardService->getPemesananBerisikoExpired();

        // ===== Layer 3: Analitik =====
        $combinedChart = $this->tiketDashboardService->getCombinedChart($pendapatanPeriode, $pendapatanOffset);
        $penjualanChart = $this->tiketDashboardService->getPenjualanChart();
        $pengunjungChart = $this->tiketDashboardService->getPengunjungChart($pengunjungOffset);
        $statistikCepat = $this->tiketDashboardService->getStatistikCepat();
        $visitorByCategory = $this->tiketDashboardService->getVisitorByTicketCategory();

        return view('tickets.dashboard.index', compact(
            'kpi',
            'refundStatistics',
            'scanStatus',
            'scanStatusHariIni',
            'kapasitasHariIni',
            'pemesananBerisiko',
            'combinedChart',
            'penjualanChart',
            'pengunjungChart',
            'statistikCepat',
            'visitorByCategory',
            'periode',
            'pendapatanOffset',
            'pendapatanPeriode',
            'pengunjungOffset'
        ));
    }

    public function chartData(Request $request)
    {
        $periode = $request->get('periode', 'bulanan');
        $pendapatanOffset = (int) $request->get('pendapatan_offset', 0);
        $pendapatanPeriode = $request->get('pendapatan_periode', $periode);
        $pengunjungOffset = (int) $request->get('pengunjung_offset', 0);

        return response()->json([
            'combined' => $this->tiketDashboardService->getCombinedChart($pendapatanPeriode, $pendapatanOffset),
            'penjualan' => $this->tiketDashboardService->getPenjualanChart(),
            'pengunjung' => $this->tiketDashboardService->getPengunjungChart($pengunjungOffset),
        ]);
    }
}