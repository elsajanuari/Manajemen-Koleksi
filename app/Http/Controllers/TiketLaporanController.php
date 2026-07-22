<?php
// app/Http/Controllers/TiketLaporanController.php

namespace App\Http\Controllers;

use App\Exports\TiketLaporanPendapatanExport;
use App\Exports\TiketLaporanPenjualanExport;
use App\Exports\TiketLaporanPengunjungExport;
use App\Exports\TiketLaporanTransaksiExport;
use App\Exports\TiketLaporanMetodePembayaranExport;
use App\Http\Controllers\Controller;
use App\Services\TiketLaporanService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class TiketLaporanController extends Controller
{
    protected $laporanService;

    public function __construct(TiketLaporanService $laporanService)
    {
        $this->laporanService = $laporanService;
    }

    public function pendapatan(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $groupBy = $request->get('group_by', 'daily');

        $data = $this->laporanService->getLaporanPendapatan($startDate, $endDate, $groupBy);
        
        return view('tickets.laporan.pendapatan', array_merge($data, [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'group_by' => $groupBy,
        ]));
    }

    public function exportPendapatan(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $groupBy = $request->get('group_by', 'daily');
        $format = $request->get('format', 'excel');

        $data = $this->laporanService->getLaporanPendapatan($startDate, $endDate, $groupBy);
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('tickets.laporan.partials.pdf-pendapatan', $data);
            return $pdf->download('laporan-pendapatan-' . $startDate . '-' . $endDate . '.pdf');
        }

        return Excel::download(new TiketLaporanPendapatanExport($data), 'laporan-pendapatan-' . $startDate . '-' . $endDate . '.xlsx');
    }

    public function penjualan(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $data = $this->laporanService->getLaporanPenjualan($startDate, $endDate);

        return view('tickets.laporan.penjualan', [
            'data' => $data,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    public function exportPenjualan(Request $request)
{
    $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

    $data = $this->laporanService->getLaporanPenjualan($startDate, $endDate);

    return Excel::download(new TiketLaporanPenjualanExport($data, $startDate, $endDate), 'laporan-penjualan-' . $startDate . '-' . $endDate . '.xlsx');
}

    public function exportPenjualanPdf(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $data = $this->laporanService->getLaporanPenjualan($startDate, $endDate);
        $totalPendapatan = array_sum(array_column($data, 'total_pendapatan'));

        $pdf = Pdf::loadView('tickets.laporan.partials.pdf-penjualan', [
            'data' => $data,
            'totalPendapatan' => $totalPendapatan,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return $pdf->download('laporan-penjualan-' . $startDate . '-' . $endDate . '.pdf');
    }

    public function transaksi(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $status = $request->get('status', 'all');
        $metode = $request->get('metode', 'all');

        $transaksi = $this->laporanService->getLaporanTransaksi($startDate, $endDate, $status, $metode);

        return view('tickets.laporan.transaksi', [
            'transaksi' => $transaksi,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status,
            'metode' => $metode,
        ]);
    }

    public function exportTransaksi(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $status = $request->get('status', 'all');
        $metode = $request->get('metode', 'all');

        $transaksi = $this->laporanService->getLaporanTransaksi($startDate, $endDate, $status, $metode);

        return Excel::download(new TiketLaporanTransaksiExport($transaksi), 'laporan-transaksi-' . $startDate . '-' . $endDate . '.xlsx');
    }

    public function exportTransaksiPdf(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $status = $request->get('status', 'all');
        $metode = $request->get('metode', 'all');

        $transaksi = $this->laporanService->getLaporanTransaksi($startDate, $endDate, $status, $metode);
        
        $allTransaksi = $transaksi->items();

        $pdf = Pdf::loadView('tickets.laporan.partials.pdf-transaksi', [
            'transaksi' => $allTransaksi,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status,
            'metode' => $metode,
        ]);

        return $pdf->download('laporan-transaksi-' . $startDate . '-' . $endDate . '.pdf');
    }
}