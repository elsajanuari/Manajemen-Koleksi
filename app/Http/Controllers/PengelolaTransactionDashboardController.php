<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Penyewaan;
use App\Models\Pembelian;
use App\Models\Payment;
use App\Models\Painting;
use App\Models\DepositRefund;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use App\Exports\Sheets\RingkasanSheet;
use App\Exports\Sheets\SalesInsightSheet;
use App\Exports\Sheets\RentalInsightSheet;
use App\Exports\Sheets\PopularCollectionsSheet;

class PengelolaTransactionDashboardController extends Controller
{
    // ─── Helpers ────────────────────────────────────────────────────────────────

    /**
     * Apply date range + type guard to a Penyewaan / Pembelian query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $dateFrom   Y-m-d
     * @param  string|null  $dateTo     Y-m-d
     * @param  string       $dateColumn column to filter on
     */
    private function applyDateRange($query, ?string $dateFrom, ?string $dateTo, string $dateColumn = 'created_at')
    {
        if ($dateFrom) {
            $query->whereDate($dateColumn, '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate($dateColumn, '<=', $dateTo);
        }
        return $query;
    }

    /**
     * Hitung total pendapatan (sewa + jual) berbasis transaksi 'selesai',
     * selaras dengan Insight Penjualan & Insight Penyewaan.
     *
     * @return array{sewa: int, beli: int, total: int}
     */
    private function calculateTotalPendapatan(?string $dateFrom, ?string $dateTo, string $type): array
    {
        $pendapatanSewa = 0;
        $pendapatanBeli = 0;

        if (in_array($type, ['all', 'penyewaan'])) {
            $selesaiPenyewaanIds = Penyewaan::where('status', 'selesai')->pluck('id');

            $pendapatanSewa = (int) Payment::whereNotNull('paid_at')
                ->whereIn('penyewaan_id', $selesaiPenyewaanIds)
                ->when($dateFrom, fn($q) => $q->whereDate('paid_at', '>=', $dateFrom))
                ->when($dateTo,   fn($q) => $q->whereDate('paid_at', '<=', $dateTo))
                ->sum('gross_amount');
        }

        if (in_array($type, ['all', 'pembelian'])) {
            $pendapatanBeli = (int) Pembelian::where('status', 'selesai')
                ->when($dateFrom, fn($q) => $q->whereDate('completed_at', '>=', $dateFrom))
                ->when($dateTo,   fn($q) => $q->whereDate('completed_at', '<=', $dateTo))
                ->sum('total_bayar');
        }

        return [
            'sewa'  => $pendapatanSewa,
            'beli'  => $pendapatanBeli,
            'total' => $pendapatanSewa + $pendapatanBeli,
        ];
    }

       // ── HELPER: ambil semua data export ──────────────────────────────────
    private function gatherExportData(Request $request): array
    {
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');
        $type     = $request->get('type', 'all');
 
        // ── KPI ─────────────────────────────────────────────────────────
        $penyewaanQ = \App\Models\Penyewaan::query();
        $pembelianQ = \App\Models\Pembelian::query();
 
        if ($dateFrom) { $penyewaanQ->whereDate('created_at', '>=', $dateFrom); $pembelianQ->whereDate('created_at', '>=', $dateFrom); }
        if ($dateTo)   { $penyewaanQ->whereDate('created_at', '<=', $dateTo);   $pembelianQ->whereDate('created_at', '<=', $dateTo); }
 
        $pendapatan = $this->calculateTotalPendapatan($dateFrom, $dateTo, $type);
        $pendapatanSewa = $pendapatan['sewa'];
        $pendapatanBeli = $pendapatan['beli'];

        $totalSewaCount = $penyewaanQ->count();
        $totalBeliCount = $pembelianQ->count();
 
        $kpi = [
            'totalPendapatan'    => $pendapatanSewa + $pendapatanBeli,
            'pendapatanSewa'     => $pendapatanSewa,
            'pendapatanBeli'     => $pendapatanBeli,
            'totalDepositDitahan'=> in_array($type, ['all', 'penyewaan'])
                    ? (int) \App\Models\Penyewaan::query()
                        ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
                        ->when($dateTo,   fn($q) => $q->whereDate('created_at', '<=', $dateTo))
                        ->where('deposit_status', 'paid')
                        ->sum('deposit_amount')
                    : 0,            
            'refundDeposit'      => in_array($type, ['all', 'penyewaan'])
                    ? (int) \App\Models\DepositRefund::query()
                        ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
                        ->when($dateTo,   fn($q) => $q->whereDate('created_at', '<=', $dateTo))
                        ->sum('refund_amount')
                    : 0,                    
            'totalTransactions'  => $totalSewaCount + $totalBeliCount,
            'totalSewa'          => $totalSewaCount,
            'totalBeli'          => $totalBeliCount,
            'penyewaanAktif'     => \App\Models\Penyewaan::where('status', 'aktif')->count(),
            'sewaSelesai'        => \App\Models\Penyewaan::where('status', 'selesai')->count(),
            'penjualanSelesai'   => \App\Models\Pembelian::where('status', 'selesai')->count(),
            'menungguVerifikasi' => \App\Models\Penyewaan::where('status', 'menunggu_verifikasi')->count()
                                  + \App\Models\Pembelian::where('status', 'menunggu_verifikasi')->count(),
            'sedangPengiriman'   => \App\Models\Penyewaan::where('status', 'pengiriman')->count()
                                  + \App\Models\Pembelian::where('status', 'pengiriman')->count(),
            'sedangPengembalian' => \App\Models\Penyewaan::where('status', 'pengembalian')->count(),
        ];
 
        // ── Popular ──────────────────────────────────────────────────────
        $rentQ = \App\Models\Penyewaan::select('painting_id', DB::raw('count(*) as cnt'))
            ->groupBy('painting_id');
        if ($dateFrom) $rentQ->whereDate('created_at', '>=', $dateFrom);
        if ($dateTo)   $rentQ->whereDate('created_at', '<=', $dateTo);

        $popularRaw = \App\Models\Painting::select('paintings.id', 'paintings.title')
            ->addSelect(DB::raw('COALESCE(r.cnt, 0) as rent_count'))
            ->leftJoinSub($rentQ, 'r', fn($j) => $j->on('paintings.id', '=', 'r.painting_id'))
            ->orderByRaw('COALESCE(r.cnt, 0) DESC')
            ->limit(10)
            ->get()
            ->map(fn($r) => [
                'id'         => $r->id,
                'title'      => $r->title,
                'rent_count' => (int) $r->rent_count,
            ])
            ->toArray();
 
        // ── Sales Insight ────────────────────────────────────────────────
        [$salesRows, $salesSummary] = $this->buildSalesData($dateFrom, $dateTo);
 
        // ── Rental Insight ───────────────────────────────────────────────
        [$rentalRows, $rentalSummary] = $this->buildRentalData($dateFrom, $dateTo);
 
        // ── Revenue (12 bulan terakhir) ────────────────────────────────────
        $revenue = $this->buildRevenueData($type);

        // ── Status Distribution ─────────────────────────────────────────
        $statusDistribution = $this->buildStatusDistribution($dateFrom, $dateTo, $type);

        // ── Filter labels ────────────────────────────────────────────────
        $activeFilters = [];
        if ($type !== 'all') $activeFilters['Tipe'] = ucfirst($type);
        if ($dateFrom)        $activeFilters['Dari'] = $dateFrom;
        if ($dateTo)          $activeFilters['Sampai'] = $dateTo;

        return compact('kpi', 'popularRaw', 'salesRows', 'salesSummary', 'rentalRows', 'rentalSummary', 'revenue', 'statusDistribution', 'activeFilters', 'dateFrom', 'dateTo', 'type');
    }

    /**
     * Bangun data tren pendapatan bulanan (rolling 12 bulan terakhir).
     */
/**
     * Bangun data tren pendapatan bulanan (rolling 12 bulan terakhir).
     * Basis: transaksi yang sudah berstatus 'selesai', selaras dengan
     * Insight Penjualan & Insight Penyewaan.
     */
    private function buildRevenueData(string $type): array
    {
        $labels = [];
        $rental = [];
        $sales  = [];

        // Ambil sekali di luar loop untuk efisiensi
        $selesaiPenyewaanIds = in_array($type, ['all', 'penyewaan'])
            ? Penyewaan::where('status', 'selesai')->pluck('id')
            : collect();

        $now = Carbon::now();
        for ($i = 11; $i >= 0; $i--) {
            $m     = $now->copy()->subMonths($i);
            $year  = $m->year;
            $month = $m->month;

            $labels[] = $m->isoFormat('MMM YYYY');

            $rental[] = in_array($type, ['all', 'penyewaan'])
                ? (int) Payment::whereNotNull('paid_at')
                    ->whereYear('paid_at', $year)
                    ->whereMonth('paid_at', $month)
                    ->whereIn('penyewaan_id', $selesaiPenyewaanIds)
                    ->sum('gross_amount')
                : 0;

            $sales[] = in_array($type, ['all', 'pembelian'])
                ? (int) Pembelian::where('status', 'selesai')
                    ->whereYear('completed_at', $year)
                    ->whereMonth('completed_at', $month)
                    ->sum('total_bayar')
                : 0;
        }

        $rows = [];
        foreach ($labels as $i => $label) {
            $rows[] = [
                'label' => $label,
                'sewa'  => $rental[$i],
                'jual'  => $sales[$i],
                'total' => $rental[$i] + $sales[$i],
            ];
        }

        return [
            'rows'      => $rows,
            'totalSewa' => array_sum($rental),
            'totalJual' => array_sum($sales),
        ];
    }

    /**
     * Bangun data distribusi status transaksi (untuk tabel di PDF).
     */
    private function buildStatusDistribution(?string $dateFrom, ?string $dateTo, string $type): array
    {
        $statusMeta = [
            'aktif'                          => ['label' => 'Aktif',                         'color' => '#3b82f6'],
            'selesai'                        => ['label' => 'Selesai',                       'color' => '#22c55e'],
            'menunggu_verifikasi'            => ['label' => 'Menunggu Verifikasi',           'color' => '#f59e0b'],
            'menunggu_pembayaran'            => ['label' => 'Menunggu Pembayaran',           'color' => '#f97316'],
            'pengiriman'                     => ['label' => 'Pengiriman',                    'color' => '#818cf8'],
            'dikirim'                        => ['label' => 'Dikirim',                       'color' => '#818cf8'],
            'dalam_pengiriman'               => ['label' => 'Dalam Pengiriman',              'color' => '#818cf8'],
            'pengembalian'                   => ['label' => 'Pengembalian',                  'color' => '#f97316'],
            'ditolak'                        => ['label' => 'Ditolak',                       'color' => '#ef4444'],
            'dibatalkan'                     => ['label' => 'Dibatalkan',                    'color' => '#ef4444'],
            'pembayaran_berhasil'            => ['label' => 'Pembayaran Berhasil',           'color' => '#34d399'],
            'menunggu_konfirmasi_refund'     => ['label' => 'Menunggu Konfirmasi Refund',    'color' => '#a78bfa'],
            'menunggu_ttd_pengembalian'      => ['label' => 'Menunggu TTD Pengembalian',     'color' => '#94a3b8'],
            'menunggu_pembayaran_kerusakan'  => ['label' => 'Menunggu Pembayaran Kerusakan', 'color' => '#fb923c'],
            'menunggu_konfirmasi_selesai'    => ['label' => 'Menunggu Konfirmasi Selesai',   'color' => '#38bdf8'],
        ];

        $merged = [];

        if (in_array($type, ['all', 'penyewaan'])) {
            $q = Penyewaan::select('status', DB::raw('count(*) as cnt'))->groupBy('status');
            $this->applyDateRange($q, $dateFrom, $dateTo, 'created_at');
            foreach ($q->get() as $r) {
                $merged[$r->status] = ($merged[$r->status] ?? 0) + (int) $r->cnt;
            }
        }

        if (in_array($type, ['all', 'pembelian'])) {
            $q = Pembelian::select('status', DB::raw('count(*) as cnt'))->groupBy('status');
            $this->applyDateRange($q, $dateFrom, $dateTo, 'created_at');
            foreach ($q->get() as $r) {
                $merged[$r->status] = ($merged[$r->status] ?? 0) + (int) $r->cnt;
            }
        }

        arsort($merged);

        $total = array_sum($merged);

        $rows = [];
        foreach ($merged as $status => $cnt) {
            $meta = $statusMeta[$status] ?? null;
            $rows[] = [
                'label'   => $meta['label'] ?? str_replace('_', ' ', ucfirst($status)),
                'color'   => $meta['color'] ?? '#94a3b8',
                'count'   => $cnt,
                'percent' => $total > 0 ? round(($cnt / $total) * 100, 1) : 0,
            ];
        }

        return [
            'rows'  => $rows,
            'total' => $total,
        ];
    }
 
    private function buildSalesData(?string $dateFrom, ?string $dateTo): array
    {
        $q = \App\Models\Pembelian::with(['painting:id,title', 'user:id,name', 'payments'])
            ->where('status', 'selesai')
            ->when($dateFrom, fn($q) => $q->whereDate('completed_at', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('completed_at', '<=', $dateTo))
            ->orderByDesc('harga_beli');
    
        $rows = $q->get()->map(fn($p) => [
            'judul'        => $p->painting?->title ?? '#'.$p->painting_id,
            'pembeli'      => $p->nama_lengkap ?? $p->company_name ?? $p->user?->name ?? '—',
            'buyer_type'   => $p->buyer_type ?? 'b2c',
            'harga_beli'   => (float) ($p->harga_beli ?? 0),
            'shipping_cost'=> (float) ($p->shipping_cost ?? 0),
            'total_bayar'  => (float) ($p->total_bayar ?? (($p->harga_beli ?? 0) + ($p->shipping_cost ?? 0))),
            'completed_at' => $p->completed_at?->format('d M Y'),
            'url'          => route('pengelola.pembelian.show', $p),
        ])->toArray();
    
        $summary = [
            'total_terjual'  => count($rows),
            'total_nilai'    => array_sum(array_column($rows, 'total_bayar')),
            'termahal_judul' => $rows[0]['judul'] ?? '—',
            'termahal_harga' => $rows[0]['harga_beli'] ?? null,
        ];
    
        return [$rows, $summary];
    }
    
    private function buildRentalData(?string $dateFrom, ?string $dateTo): array
    {
        $items = \App\Models\Penyewaan::with(['painting:id,title,daily_rate', 'user:id,name', 'payments'])
            ->where('status', 'selesai')
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->get();
    
        $rows = $items->map(function ($r) {
            $durationDays = $r->duration_days; // accessor: diffInDays(start, end) + 1
    
            $subtotal = (int) ($r->subtotal_amount ?? 0);
            if ($subtotal === 0 && $durationDays > 0) {
                $subtotal = (int) (($r->painting->daily_rate ?? 0) * $durationDays);
            }
    
            $deposit      = (int) ($r->deposit_amount ?? round($subtotal * 0.5));
            $shippingCost = (int) ($r->shipping_cost ?? 0);
            $totalBayar   = (int) ($r->total_bayar ?? 0);
            if ($totalBayar === 0) {
                $totalBayar = $subtotal + $deposit + $shippingCost;
            }
    
            return [
                'id'           => $r->id,
                'judul'        => $r->painting?->title ?? '#'.$r->painting_id,
                'penyewa'      => $r->contact_name ?? $r->user?->name ?? '—',
                'rental_type'  => $r->rental_type ?? 'perseorangan',
                'duration_days'=> (int) $durationDays,
                'subtotal'     => $subtotal,
                'deposit'      => $deposit,
                'shipping_cost'=> $shippingCost,
                'total_bayar'  => $totalBayar,
                'start_date'   => $r->start_date?->format('d M Y'),
                'end_date'     => $r->end_date?->format('d M Y'),
                'url'          => route('pengelola.penyewaan.show', $r),
            ];
        })->sortByDesc('subtotal')->values();
    
        $paymentQ = \App\Models\Payment::whereNotNull('paid_at')
            ->whereIn('penyewaan_id', $rows->pluck('id'));
        if ($dateFrom) $paymentQ->whereDate('paid_at', '>=', $dateFrom);
        if ($dateTo)   $paymentQ->whereDate('paid_at', '<=', $dateTo);
        $totalPendapatan = (int) $paymentQ->sum('gross_amount');
    
        $rowsArray = $rows->map(fn($r) => collect($r)->except('id')->all())->toArray();
    
        $summary = [
            'total_selesai'     => count($rowsArray),
            'total_pendapatan'  => $totalPendapatan,
            'termahal_judul'    => $rowsArray[0]['judul'] ?? '—',
            'termahal_subtotal' => $rowsArray[0]['subtotal'] ?? null,
        ];
    
        return [$rowsArray, $summary];
    }
 
    // ── EXPORT PDF ───────────────────────────────────────────────────────
    public function exportPdf(Request $request)
    {
        $data = $this->gatherExportData($request);

        $pdf = Pdf::loadView('exports.transaction-pdf', [
            'kpi'                => $data['kpi'],
            'popular'            => $data['popularRaw'],
            'salesRows'          => $data['salesRows'],
            'salesSummary'       => $data['salesSummary'],
            'rentalRows'         => $data['rentalRows'],
            'rentalSummary'      => $data['rentalSummary'],
            'revenue'            => $data['revenue'],
            'statusDistribution' => $data['statusDistribution'],
            'activeFilters'      => $data['activeFilters'],
        ]);

        $pdf->setPaper('A4', 'landscape');
        $pdf->set_option('defaultFont', 'DejaVu Sans');
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isRemoteEnabled', false);

        $filename = 'laporan-transaksi-'.now()->format('Ymd-His').'.pdf';

        return $pdf->download($filename);
    }
 
    // ── EXPORT EXCEL ─────────────────────────────────────────────────────
    public function exportExcel(Request $request)
    {
        $data = $this->gatherExportData($request);
 
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // hapus sheet default
 
        // ── Sheet 1: Ringkasan ───────────────────────────────────────────
        $ringkasanSheet = new RingkasanSheet([
            'date_from' => $data['dateFrom'],
            'date_to'   => $data['dateTo'],
            'type'      => $data['type'],
            'kpi'       => $data['kpi'],
        ]);
        $ws1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Ringkasan');
        $spreadsheet->addSheet($ws1);
        foreach ($ringkasanSheet->array() as $i => $row) {
            $ws1->fromArray([$row], null, 'A'.($i + 1));
        }
        $ringkasanSheet->styles($ws1);
        foreach ($ringkasanSheet->columnWidths() as $col => $width) {
            $ws1->getColumnDimension($col)->setWidth($width);
        }
 
        // ── Sheet 2: Penjualan ───────────────────────────────────────────
        $salesSheet = new SalesInsightSheet();
        $ws2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Penjualan');
        $spreadsheet->addSheet($ws2);
        foreach ($salesSheet->headings() as $i => $row) {
            if (!empty(array_filter($row))) {
                $ws2->fromArray([$row], null, 'A'.($i + 1));
            }
        }
        $salesSheet->styles($ws2);
        foreach ($salesSheet->columnWidths() as $col => $width) {
            $ws2->getColumnDimension($col)->setWidth($width);
        }
        $salesSheet->populateData($ws2, $data['salesRows'], 5);
 
        // ── Sheet 3: Penyewaan ───────────────────────────────────────────
        $rentalSheet = new RentalInsightSheet();
        $ws3 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Penyewaan');
        $spreadsheet->addSheet($ws3);
        foreach ($rentalSheet->headings() as $i => $row) {
            if (!empty(array_filter($row))) {
                $ws3->fromArray([$row], null, 'A'.($i + 1));
            }
        }
        $rentalSheet->styles($ws3);
        foreach ($rentalSheet->columnWidths() as $col => $width) {
            $ws3->getColumnDimension($col)->setWidth($width);
        }
        $rentalSheet->populateData($ws3, $data['rentalRows'], 5);
 
        // ── Sheet 4: Koleksi Populer ─────────────────────────────────────
        $popSheet = new PopularCollectionsSheet();
        $ws4 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Koleksi Populer');
        $spreadsheet->addSheet($ws4);
        foreach ($popSheet->headings() as $i => $row) {
            if (!empty(array_filter($row))) {
                $ws4->fromArray([$row], null, 'A'.($i + 1));
            }
        }
        $popSheet->styles($ws4);
        foreach ($popSheet->columnWidths() as $col => $width) {
            $ws4->getColumnDimension($col)->setWidth($width);
        }
        $popSheet->populateData($ws4, $data['popularRaw'], 5);
 
        // ── Stream response ───────────────────────────────────────────────
        $filename = 'laporan-transaksi-'.now()->format('Ymd-His').'.xlsx';
 
        $writer = new XlsxWriter($spreadsheet);
 
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }
 
    // ── EXPORT CSV ───────────────────────────────────────────────────────
    public function exportCsv(Request $request)
    {
        $section  = $request->get('section', 'all');
        $data     = $this->gatherExportData($request);
        $filename = 'transaksi-'.$section.'-'.now()->format('Ymd-His').'.csv';
 
        $rows = [];
 
        if ($section === 'all' || $section === 'ringkasan') {
            $rows[] = ['=== RINGKASAN KPI ==='];
            $rows[] = ['Metrik', 'Nilai'];
            $rows[] = ['Total Pendapatan (Rp)',     $data['kpi']['totalPendapatan']];
            $rows[] = ['Deposit Ditahan (Rp)',      $data['kpi']['totalDepositDitahan']];
            $rows[] = ['Refund Deposit (Rp)',       $data['kpi']['refundDeposit']];
            $rows[] = ['Total Transaksi',           $data['kpi']['totalTransactions']];
            $rows[] = ['Sewa Aktif',                $data['kpi']['penyewaanAktif']];
            $rows[] = ['Penjualan Selesai',         $data['kpi']['penjualanSelesai']];
            $rows[] = ['Perlu Verifikasi',          $data['kpi']['menungguVerifikasi']];
            $rows[] = ['Dalam Pengiriman',          $data['kpi']['sedangPengiriman']];
            $rows[] = [];
        }
 
        if ($section === 'all' || $section === 'penjualan') {
            $rows[] = ['=== INSIGHT PENJUALAN ==='];
            $rows[] = ['No', 'Koleksi', 'Pembeli', 'Tipe Pembeli', 'Harga Beli (Rp)', 'Ongkos Kirim (Rp)', 'Total Bayar (Rp)', 'Tgl Selesai'];
            foreach ($data['salesRows'] as $i => $r) {
                $rows[] = [
                    $i + 1,
                    $r['judul'],
                    $r['pembeli'],
                    strtoupper($r['buyer_type']),
                    $r['harga_beli'],
                    $r['shipping_cost'],
                    $r['total_bayar'],
                    $r['completed_at'] ?? '—',
                ];
            }
            // Total baris
            $rows[] = [
                '', 'TOTAL', '', '',
                array_sum(array_column($data['salesRows'], 'harga_beli')),
                array_sum(array_column($data['salesRows'], 'shipping_cost')),
                array_sum(array_column($data['salesRows'], 'total_bayar')),
                '',
            ];
            $rows[] = [];
        }
 
        if ($section === 'all' || $section === 'penyewaan') {
            $rows[] = ['=== INSIGHT PENYEWAAN ==='];
            $rows[] = ['No', 'Koleksi', 'Penyewa', 'Tipe Penyewa', 'Durasi (Hari)', 'Subtotal (Rp)', 'Deposit (Rp)', 'Ongkir (Rp)', 'Total Bayar (Rp)', 'Tgl Mulai', 'Tgl Selesai'];
            foreach ($data['rentalRows'] as $i => $r) {
                $rows[] = [
                    $i + 1,
                    $r['judul'],
                    $r['penyewa'],
                    ucfirst($r['rental_type']),
                    $r['duration_days'],
                    $r['subtotal'],
                    $r['deposit'],
                    $r['shipping_cost'],
                    $r['total_bayar'],
                    $r['start_date'] ?? '—',
                    $r['end_date']   ?? '—',
                ];
            }
            $rows[] = [
                '', 'TOTAL', '', '', '',
                array_sum(array_column($data['rentalRows'], 'subtotal')),
                array_sum(array_column($data['rentalRows'], 'deposit')),
                array_sum(array_column($data['rentalRows'], 'shipping_cost')),
                array_sum(array_column($data['rentalRows'], 'total_bayar')),
                '', '',
            ];
            $rows[] = [];
        }
 
        if ($section === 'all' || $section === 'populer') {
            $rows[] = ['=== KOLEKSI TERPOPULER ==='];
            $rows[] = ['Peringkat', 'Nama Koleksi', 'Jumlah Disewa'];
            foreach ($data['popularRaw'] as $i => $r) {
                $rows[] = [$i + 1, $r['title'] ?? '#'.$r['id'], $r['rent_count']];
            }
        }
 
        // ── Build CSV string ─────────────────────────────────────────────
        $csvContent = "\xEF\xBB\xBF"; // BOM untuk Excel agar UTF-8 terbaca benar
        foreach ($rows as $row) {
            $csvContent .= implode(',', array_map(function ($cell) {
                // Escape cell: bungkus dengan quotes jika ada koma/newline/quotes
                $cell = (string) $cell;
                if (str_contains($cell, ',') || str_contains($cell, '"') || str_contains($cell, "\n")) {
                    $cell = '"' . str_replace('"', '""', $cell) . '"';
                }
                return $cell;
            }, $row)) . "\n";
        }
 
        return response($csvContent, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
        ]);
    }

    // ─── Dashboard (Blade) ───────────────────────────────────────────────────────

    /**
     * Main dashboard view.
     * Supports optional filter: ?date_from=&date_to=&type=all|penyewaan|pembelian
     */
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');
        $type     = $request->get('type', 'all'); // all | penyewaan | pembelian

        // ── KPI ─────────────────────────────────────────────────────────────────
        $penyewaanQ = fn () => $this->applyDateRange(Penyewaan::query(), $dateFrom, $dateTo, 'created_at');
        $pembelianQ = fn () => $this->applyDateRange(Pembelian::query(), $dateFrom, $dateTo, 'created_at');

        $totalTransactions = 0;
        if (in_array($type, ['all', 'penyewaan'])) $totalTransactions += $penyewaanQ()->count();
        if (in_array($type, ['all', 'pembelian']))  $totalTransactions += $pembelianQ()->count();

        $penyewaanAktif = in_array($type, ['all', 'penyewaan'])
            ? $penyewaanQ()->where('status', 'aktif')->count()
            : 0;

        $penjualanSelesai = in_array($type, ['all', 'pembelian'])
            ? $pembelianQ()->where('status', 'selesai')->count()
            : 0;

        $menungguVerifikasi = 0;
        if (in_array($type, ['all', 'penyewaan'])) $menungguVerifikasi += $penyewaanQ()->where('status', 'menunggu_verifikasi')->count();
        if (in_array($type, ['all', 'pembelian']))  $menungguVerifikasi += $pembelianQ()->where('status', 'menunggu_verifikasi')->count();

        $sedangPengiriman = 0;
        if (in_array($type, ['all', 'penyewaan'])) $sedangPengiriman += $penyewaanQ()->where('status', 'pengiriman')->count();
        if (in_array($type, ['all', 'pembelian']))  $sedangPengiriman += $pembelianQ()->whereIn('status', ['dikirim', 'pengiriman', 'dalam_pengiriman'])->count();

        $sedangPengembalian = in_array($type, ['all', 'penyewaan'])
            ? $penyewaanQ()->where('status', 'pengembalian')->count()
            : 0;

        // ── Financial ───────────────────────────────────────────────────────────
        $pendapatan = $this->calculateTotalPendapatan($dateFrom, $dateTo, $type);
        $totalPendapatan = $pendapatan['total'];

        // Deposit ditahan & refund: type filter applies
        $totalDepositDitahan = in_array($type, ['all', 'penyewaan'])
            ? (int) $penyewaanQ()->where('deposit_status', 'paid')->sum('deposit_amount')
            : 0;

        $refundDepositQ = DepositRefund::query();
        if ($dateFrom) $refundDepositQ->whereDate('created_at', '>=', $dateFrom);
        if ($dateTo)   $refundDepositQ->whereDate('created_at', '<=', $dateTo);
        $refundDeposit = in_array($type, ['all', 'penyewaan']) ? (int) $refundDepositQ->sum('refund_amount') : 0;

        // When called via AJAX (filter bar), return JSON instead of view
        if ($request->ajax() || $request->get('_json')) {
            return response()->json([
                'totalTransactions'   => $totalTransactions,
                'penyewaanAktif'      => $penyewaanAktif,
                'penjualanSelesai'    => $penjualanSelesai,
                'menungguVerifikasi'  => $menungguVerifikasi,
                'sedangPengiriman'    => $sedangPengiriman,
                'sedangPengembalian'  => $sedangPengembalian,
                'totalPendapatan'     => $totalPendapatan,
                'totalDepositDitahan' => $totalDepositDitahan,
                'refundDeposit'       => $refundDeposit,
            ]);
        }

        return view('pengelola.transactions.dashboard', compact(
            'totalTransactions',
            'penyewaanAktif',
            'penjualanSelesai',
            'menungguVerifikasi',
            'sedangPengiriman',
            'sedangPengembalian',
            'totalPendapatan',
            'totalDepositDitahan',
            'refundDeposit',

        ))->with([
            'activeFilters' => [
                'date_from' => $dateFrom,
                'date_to'   => $dateTo,
                'type'      => $type,
            ],
        ]);
    }

    // ─── AJAX: Revenue Chart ─────────────────────────────────────────────────────

    /**
     * GET /pengelola/transactions/data/revenue
     *
     * Query params:
     *   year   int         (default: current year; use "all" for last-12-months rolling)
     *   type   all|penyewaan|pembelian
     */
    public function revenueData(Request $request)
    {
        $yearParam = $request->get('year', 'rolling'); // 'rolling' = last 12 months
        $type      = $request->get('type', 'all');

        $labels = [];
        $rental = [];
        $sales  = [];

        // Ambil sekali, dipakai untuk filter Payment milik penyewaan yang selesai
        $selesaiPenyewaanIds = in_array($type, ['all', 'penyewaan'])
            ? Penyewaan::where('status', 'selesai')->pluck('id')
            : collect();

        if ($yearParam === 'rolling' || $yearParam === '') {
            // Last 12 months
            $now = Carbon::now();
            for ($i = 11; $i >= 0; $i--) {
                $m     = $now->copy()->subMonths($i);
                $year  = $m->year;
                $month = $m->month;

                $labels[] = $m->isoFormat('MMM YYYY');

                $rental[] = in_array($type, ['all', 'penyewaan'])
                    ? (int) Payment::whereNotNull('paid_at')
                        ->whereYear('paid_at', $year)
                        ->whereMonth('paid_at', $month)
                        ->whereIn('penyewaan_id', $selesaiPenyewaanIds)
                        ->sum('gross_amount')
                    : 0;

                $sales[] = in_array($type, ['all', 'pembelian'])
                    ? (int) Pembelian::where('status', 'selesai')
                        ->whereYear('completed_at', $year)
                        ->whereMonth('completed_at', $month)
                        ->sum('total_bayar')
                    : 0;
            }
        } else {
            // Specific year — show Jan–Dec
            $year = (int) $yearParam;
            for ($month = 1; $month <= 12; $month++) {
                $labels[] = Carbon::create($year, $month, 1)->isoFormat('MMM');

                $rental[] = in_array($type, ['all', 'penyewaan'])
                    ? (int) Payment::whereNotNull('paid_at')
                        ->whereYear('paid_at', $year)
                        ->whereMonth('paid_at', $month)
                        ->whereIn('penyewaan_id', $selesaiPenyewaanIds)
                        ->sum('gross_amount')
                    : 0;

                $sales[] = in_array($type, ['all', 'pembelian'])
                    ? (int) Pembelian::where('status', 'selesai')
                        ->whereYear('completed_at', $year)
                        ->whereMonth('completed_at', $month)
                        ->sum('total_bayar')
                    : 0;
            }
        }

        return response()->json([
            'labels' => $labels,
            'rental' => $rental,
            'sales'  => $sales,
        ]);
    }

    // ─── AJAX: Status Donut ──────────────────────────────────────────────────────

    /**
     * GET /pengelola/transactions/data/status
     *
     * Query params:
     *   date_from  Y-m-d
     *   date_to    Y-m-d
     *   type       all|penyewaan|pembelian
     */
    public function statusDistribution(Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');
        $type     = $request->get('type', 'all');

        $penyewaan = collect();
        $pembelian = collect();

        if (in_array($type, ['all', 'penyewaan'])) {
            $q = Penyewaan::select('status', DB::raw('count(*) as cnt'))->groupBy('status');
            $this->applyDateRange($q, $dateFrom, $dateTo, 'created_at');
            $penyewaan = $q->get()->pluck('cnt', 'status');
        }

        if (in_array($type, ['all', 'pembelian'])) {
            $q = Pembelian::select('status', DB::raw('count(*) as cnt'))->groupBy('status');
            $this->applyDateRange($q, $dateFrom, $dateTo, 'created_at');
            $pembelian = $q->get()->pluck('cnt', 'status');
        }

        return response()->json([
            'penyewaan' => $penyewaan,
            'pembelian' => $pembelian,
        ]);
    }

    // ─── AJAX: Popular Collections ───────────────────────────────────────────────

    /**
     * GET /pengelola/transactions/data/popular
     *
     * Query params:
     *   date_from  Y-m-d
     *   date_to    Y-m-d
     *   type       all|penyewaan|pembelian
     */
    public function popularCollections(Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');
    
        $rentQ = Penyewaan::select('painting_id', DB::raw('count(*) as cnt'))
            ->groupBy('painting_id');
        if ($dateFrom) $rentQ->whereDate('created_at', '>=', $dateFrom);
        if ($dateTo)   $rentQ->whereDate('created_at', '<=', $dateTo);
    
        $data = Painting::select('paintings.id', 'paintings.title')
            ->addSelect(DB::raw('COALESCE(r.cnt, 0) as rent_count'))
            ->leftJoinSub($rentQ, 'r', fn ($j) => $j->on('paintings.id', '=', 'r.painting_id'))
            ->orderByRaw('COALESCE(r.cnt, 0) DESC')
            ->limit(10)
            ->get();
    
        return response()->json($data);
    }

    public function salesInsight(Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');

        $query = Pembelian::with('painting', 'user')
            ->where('status', 'selesai');

        // Filter berdasarkan completed_at
        if ($dateFrom) $query->whereDate('completed_at', '>=', $dateFrom);
        if ($dateTo)   $query->whereDate('completed_at', '<=', $dateTo);

        $items = $query->orderBy('harga_beli', 'desc')->get();

        $summary = [
            'total_terjual'    => $items->count(),
            'total_nilai'      => (int) $items->sum('total_bayar'),
            'termahal_harga'   => (int) $items->max('harga_beli'),
            'termahal_judul'   => $items->sortByDesc('harga_beli')->first()?->painting?->title ?? '-',
        ];

        $rows = $items->map(fn ($p) => [
            'id'           => $p->id,
            'judul'        => $p->painting?->title ?? '-',
            'pembeli'      => $p->nama_lengkap ?? $p->user?->name ?? '-',
            'buyer_type'   => $p->buyer_type,          // b2c / b2b
            'harga_beli'   => (int) $p->harga_beli,
            'shipping_cost'=> (int) $p->shipping_cost,
            'total_bayar'  => (int) $p->total_bayar,
            'completed_at' => $p->completed_at?->toDateString(),
            'url'          => route('pengelola.pembelian.show', $p),
        ]);

        return response()->json([
            'summary' => $summary,
            'rows'    => $rows,
        ]);
    }

    public function rentalInsight(Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');
    
        $query = Penyewaan::with('painting', 'user')
            ->where('status', 'selesai');
    
        if ($dateFrom) $query->whereDate('submitted_at', '>=', $dateFrom);
        if ($dateTo)   $query->whereDate('submitted_at', '<=', $dateTo);
    
        $items = $query->orderByDesc('subtotal_amount')->get();
    
        // Hitung subtotal fallback dari daily_rate jika kolom kosong
        $items = $items->map(function ($p) {
            $durationDays = $p->duration_days; // accessor: diffInDays(start, end) + 1
    
            // Fallback: hitung dari daily_rate jika subtotal_amount kosong
            $subtotal = (int) ($p->subtotal_amount ?? 0);
            if ($subtotal === 0 && $durationDays > 0) {
                $subtotal = (int) (($p->painting->daily_rate ?? 0) * $durationDays);
            }
    
            $deposit      = (int) ($p->deposit_amount ?? round($subtotal * 0.5));
            $shippingCost = (int) ($p->shipping_cost ?? 0);
            $totalBayar   = (int) ($p->total_bayar ?? 0);
            if ($totalBayar === 0) {
                $totalBayar = $subtotal + $deposit + $shippingCost;
            }
    
            $p->_subtotal      = $subtotal;
            $p->_deposit       = $deposit;
            $p->_shipping_cost = $shippingCost;
            $p->_total_bayar   = $totalBayar;
            $p->_duration_days = $durationDays;
    
            return $p;
        })->sortByDesc('_subtotal')->values();
    
        // Pendapatan dari Payment
        $paymentQ = Payment::whereNotNull('paid_at')
            ->whereIn('penyewaan_id', $items->pluck('id'));
        if ($dateFrom) $paymentQ->whereDate('paid_at', '>=', $dateFrom);
        if ($dateTo)   $paymentQ->whereDate('paid_at', '<=', $dateTo);
        $totalPendapatan = (int) $paymentQ->sum('gross_amount');
    
        $termahal = $items->first();
    
        $summary = [
            'total_selesai'     => $items->count(),
            'total_pendapatan'  => $totalPendapatan,
            'termahal_judul'    => $termahal?->painting?->title ?? '-',
            'termahal_subtotal' => $termahal?->_subtotal ?? 0,
        ];
    
        $rows = $items->map(fn ($p) => [
            'id'            => $p->id,
            'judul'         => $p->painting?->title ?? '-',
            'penyewa'       => $p->contact_name ?? $p->user?->name ?? '-',
            'rental_type'   => $p->rental_type,
            'duration_days' => $p->_duration_days,
            'start_date'    => $p->start_date?->toDateString(),
            'end_date'      => $p->end_date?->toDateString(),
            'subtotal'      => $p->_subtotal,
            'deposit'       => $p->_deposit,
            'shipping_cost' => $p->_shipping_cost,
            'total_bayar'   => $p->_total_bayar,
            'url'           => route('pengelola.penyewaan.show', $p),
        ]);
    
        return response()->json([
            'summary' => $summary,
            'rows'    => $rows,
        ]);
    }

    // ─── AJAX: Recent Activities ─────────────────────────────────────────────────

    /**
     * GET /pengelola/transactions/data/recent
     *
     * Query params:
     *   date_from  Y-m-d
     *   date_to    Y-m-d
     *   type       all|penyewaan|pembelian
     *   status     string (optional)
     */
    public function recentActivities(Request $request)
    {
        $dateFrom     = $request->get('date_from');
        $dateTo       = $request->get('date_to');
        $type         = $request->get('type', 'all');
        $statusFilter = $request->get('status');

        $penyewaan = collect();
        $pembelian = collect();

        if (in_array($type, ['all', 'penyewaan'])) {
            $q = Penyewaan::with('user', 'painting')->latest('submitted_at');
            $this->applyDateRange($q, $dateFrom, $dateTo, 'submitted_at');
            if ($statusFilter) $q->where('status', $statusFilter);
            $penyewaan = $q->take(8)->get()->map(fn ($p) => [
                'type'     => 'penyewaan',
                'id'       => $p->id,
                'user'     => $p->user?->name,
                'painting' => $p->painting?->title,
                'status'   => $p->status,
                'time'     => $p->submitted_at?->toDateTimeString(),
                'url'      => route('pengelola.penyewaan.show', $p),
            ]);
        }

        if (in_array($type, ['all', 'pembelian'])) {
            $q = Pembelian::with('user', 'painting')->latest('submitted_at');
            $this->applyDateRange($q, $dateFrom, $dateTo, 'submitted_at');
            if ($statusFilter) $q->where('status', $statusFilter);
            $pembelian = $q->take(8)->get()->map(fn ($p) => [
                'type'     => 'pembelian',
                'id'       => $p->id,
                'user'     => $p->user?->name,
                'painting' => $p->painting?->title,
                'status'   => $p->status,
                'time'     => $p->submitted_at?->toDateTimeString(),
                'url'      => route('pengelola.pembelian.show', $p),
            ]);
        }

        $merged = collect([$penyewaan, $pembelian])
            ->flatten(1)
            ->sortByDesc('time')
            ->values()
            ->take(12);

        return response()->json($merged);
    }
}