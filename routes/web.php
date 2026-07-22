<?php

use App\Http\Controllers\DataPengunjungController;
use App\Http\Controllers\MidtransNotificationController;
use App\Http\Controllers\PemesananTiketController;
use App\Http\Controllers\PengelolaPenyewaanController;
use App\Http\Controllers\PengelolaScanTiketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KoleksiController;
use App\Http\Controllers\KondisiKoleksiController;
use App\Http\Controllers\PenyewaanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ConservationActionController;
use App\Http\Controllers\PerawatanKoleksiController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ManajemenKoleksiController;
use App\Http\Controllers\TiketDashboardController;
use App\Http\Controllers\TiketLaporanController;
use App\Models\Ticket;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SerahTerimaController;
use App\Http\Controllers\KatalogMuseumController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PengelolaPembelianController;
use App\Http\Controllers\SerahTerimaPembelianController;
use App\Http\Controllers\PengelolaTransactionController;
use App\Http\Controllers\PengelolaTransactionDashboardController;
use App\Models\Penyewaan;
use App\Models\Pembelian;
use App\Models\Payment;
use App\Models\DepositRefund;
use App\Http\Controllers\CertificateController;

/*
|--------------------------------------------------------------------------
| Landing Page (Semua User)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $today = now()->timezone('Asia/Jakarta')->toDateString();
 
    $tickets = \App\Models\Ticket::with('quotas')
        ->where(function ($q) use ($today) {
            $q->whereNull('tanggal_selesai')
              ->orWhere('tanggal_selesai', '>=', $today);
        })
        ->get()
        ->groupBy('kategori');
 
    return view('welcome', compact('tickets'));
})->name('landing');

Route::post('/midtrans/notification', [MidtransNotificationController::class, 'handle'])
    ->name('midtrans.notification');
Route::get('/gallery', [KoleksiController::class, 'gallery'])->name('gallery');
Route::get('/gallery/{koleksi}', [KoleksiController::class, 'showPublic'])->name('gallery.show');
Route::get('/katalog-koleksi-museum', [KatalogMuseumController::class, 'index'])->name('katalog-museum.index');

Route::middleware(['auth', 'role:pengguna'])->group(function () {
    Route::get('/katalog-koleksi-museum/{slug}/ajukan-sewa', [KatalogMuseumController::class, 'ajukanPenyewaan'])
        ->name('katalog-museum.ajukan-sewa');
});

Route::middleware(['auth', 'role:pengguna'])->group(function () {
    Route::get('/katalog-koleksi-museum/{slug}/beli', [KatalogMuseumController::class, 'beliKoleksi'])
        ->name('katalog-museum.beli');
});

Route::get('/katalog-koleksi-museum/{slug}', [KatalogMuseumController::class, 'show'])->name('katalog-museum.show');

Route::get('/gallery', [KoleksiController::class, 'gallery'])->name('gallery');
Route::get('/sewa/{koleksi}', function () {
    return view('placeholder');
})->name('sewa');

Route::get('/penjualan', function () {
    if (auth()->check() && auth()->user()->role === 'pengguna') {
        return redirect()->route('pembelian.index');
    }
    return redirect()->route('katalog-museum.index');
})->name('penjualan.index');


/*
|--------------------------------------------------------------------------
| Dashboard & Pengelola
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:pengelola'])->group(function () {

    Route::get('/dashboard', function () {
        $tickets = Ticket::all();
        return view('dashboard');
    })->name('dashboard');

    Route::get('/manajemen-koleksi', [ManajemenKoleksiController::class, 'index'])->name('manajemen-koleksi.index');

    // Dashboard Transaksi Pengelola (controller + API endpoints)
    Route::get('/dashboard-transaksi', [PengelolaTransactionDashboardController::class, 'index'])
        ->name('pengelola.transactions.dashboard');

    Route::get('/dashboard-transaksi/data/revenue', [PengelolaTransactionDashboardController::class, 'revenueData'])
        ->name('pengelola.transactions.data.revenue');
    Route::get('/dashboard-transaksi/data/status', [PengelolaTransactionDashboardController::class, 'statusDistribution'])
        ->name('pengelola.transactions.data.status');
    Route::get('/dashboard-transaksi/data/popular', [PengelolaTransactionDashboardController::class, 'popularCollections'])
        ->name('pengelola.transactions.data.popular');
    Route::get('transactions/data/rental-insight', [PengelolaTransactionDashboardController::class, 'rentalInsight'])
        ->name('pengelola.transactions.data.rental-insight');
    Route::get('transactions/data/sales-insight', [PengelolaTransactionDashboardController::class, 'salesInsight'])
        ->name('pengelola.transactions.data.sales-insight');
    
    Route::get('/dashboard-transaksi/data/recent', [PengelolaTransactionDashboardController::class, 'recentActivities'])
        ->name('pengelola.transactions.data.recent');
    Route::get('/dashboard-transaksi/export/pdf',   [PengelolaTransactionDashboardController::class, 'exportPdf'])
        ->name('pengelola.transactions.export.pdf');
    Route::get('/dashboard-transaksi/export/excel', [PengelolaTransactionDashboardController::class, 'exportExcel'])
        ->name('pengelola.transactions.export.excel');
    Route::get('/dashboard-transaksi/export/csv',   [PengelolaTransactionDashboardController::class, 'exportCsv'])
        ->name('pengelola.transactions.export.csv');

    // ── Penyewaan ──────────────────────────────────────────────────────
    Route::get('/pengajuan-penyewaan', [PengelolaPenyewaanController::class, 'index'])
        ->name('pengelola.penyewaan.index');
    Route::get('/pengajuan-penyewaan/{penyewaan}', [PengelolaPenyewaanController::class, 'show'])
        ->name('pengelola.penyewaan.show');
    Route::get('/pengajuan-penyewaan-riwayat', [PengelolaPenyewaanController::class, 'riwayat'])
        ->name('pengelola.penyewaan.riwayat');
    Route::post('/pengajuan-penyewaan/{penyewaan}/approve', [PengelolaPenyewaanController::class, 'approve'])
        ->name('pengelola.penyewaan.approve');
    Route::post('/pengajuan-penyewaan/{penyewaan}/reject', [PengelolaPenyewaanController::class, 'reject'])
        ->name('pengelola.penyewaan.reject');
    Route::post('/pengajuan-penyewaan/{penyewaan}/request-revision', [PengelolaPenyewaanController::class, 'requestRevision'])
        ->name('pengelola.penyewaan.requestRevision');
    Route::post('/pengajuan-penyewaan/{penyewaan}/signed-agreement/review', [PengelolaPenyewaanController::class, 'reviewSignedAgreement'])
        ->name('pengelola.penyewaan.reviewSignedAgreement');

    // ==================== DASHBOARD TIKET ====================
    Route::get('/tickets/dashboard', [TiketDashboardController::class, 'index'])
        ->name('tickets.dashboard');
    Route::get('/tickets/dashboard/chart-data', [TiketDashboardController::class, 'chartData'])
        ->name('tickets.dashboard.chart-data');

    // ==================== DATA PENGUNJUNG ====================
    Route::get('/tickets/data-pengunjung', [DataPengunjungController::class, 'index'])
        ->name('tickets.data-pengunjung.index');
    Route::get('/tickets/data-pengunjung/{id}', [DataPengunjungController::class, 'show'])
        ->name('tickets.data-pengunjung.show');

    // ==================== LAPORAN TIKET ====================
    Route::prefix('/tickets/laporan')->name('tickets.laporan.')->group(function () {
        Route::get('/pendapatan', [TiketLaporanController::class, 'pendapatan'])->name('pendapatan');
        Route::get('/pendapatan/export', [TiketLaporanController::class, 'exportPendapatan'])->name('pendapatan.export');

        Route::get('/penjualan', [TiketLaporanController::class, 'penjualan'])->name('penjualan');
        Route::get('/penjualan/export', [TiketLaporanController::class, 'exportPenjualan'])->name('penjualan.export');
        Route::get('/penjualan/export-pdf', [TiketLaporanController::class, 'exportPenjualanPdf'])->name('penjualan.export-pdf');

        Route::get('/pengunjung', [TiketLaporanController::class, 'pengunjung'])->name('pengunjung');
        Route::get('/pengunjung/export', [TiketLaporanController::class, 'exportPengunjung'])->name('pengunjung.export');

        Route::get('/transaksi', [TiketLaporanController::class, 'transaksi'])->name('transaksi');
        Route::get('/transaksi/export', [TiketLaporanController::class, 'exportTransaksi'])->name('transaksi.export');
        Route::get('/transaksi/export-pdf', [TiketLaporanController::class, 'exportTransaksiPdf'])->name('transaksi.export-pdf');

        Route::get('/metode-pembayaran', [TiketLaporanController::class, 'metodePembayaran'])->name('metode-pembayaran');
        Route::get('/metode-pembayaran/export', [TiketLaporanController::class, 'exportMetodePembayaran'])->name('metode-pembayaran.export');
    });

    // ==================== VERIFIKASI DAN RIWAYAT TIKET ====================
    Route::get('/pengelola/verifikasi-tiket', [PengelolaScanTiketController::class, 'form'])
        ->name('pengelola.verifikasi-tiket.form');
    Route::post('/pengelola/verifikasi-tiket', [PengelolaScanTiketController::class, 'lookup'])
        ->name('pengelola.verifikasi-tiket.lookup');
    Route::get('/pengelola/scan-tiket/{token}', [PengelolaScanTiketController::class, 'show'])
        ->name('pengelola.scan-tiket');
    Route::post('/pengelola/scan-tiket/{token}/pakai', [PengelolaScanTiketController::class, 'tandaiTerpakai'])
        ->name('pengelola.scan-tiket.pakai');
    Route::get('/pengelola/riwayat-tiket', [PengelolaScanTiketController::class, 'riwayat'])
        ->name('pengelola.riwayat-tiket.index');
    Route::get('/pengelola/riwayat-pemesanan', [PengelolaScanTiketController::class, 'riwayatPemesanan'])
        ->name('pengelola.riwayat-pemesanan.index');
    Route::post('/pengelola/riwayat-pemesanan/{pemesananTiket}/kirim-refund', [PengelolaScanTiketController::class, 'kirimRefund'])
        ->name('pengelola.riwayat-pemesanan.kirim');
    Route::get('/pengelola/detail-refund/{pemesananTiket}', [PengelolaScanTiketController::class, 'detailRefund'])
        ->name('pengelola.detail-refund');

    // ==================== MANAJEMEN TIKET ====================
    Route::resource('/tickets', TicketController::class);

    // ==================== MANAGE JADWAL DAN KUOTA TIKET ====================
    // ── Return Review & Document ───────────────────────────────────────
    Route::get('/pengajuan-penyewaan/{penyewaan}/return-review', [PengelolaPenyewaanController::class, 'showReturnReview'])
        ->name('pengelola.penyewaan.return-review');
    Route::post('/pengajuan-penyewaan/{penyewaan}/return-review', [PengelolaPenyewaanController::class, 'processReturnReview'])
        ->name('pengelola.penyewaan.return-review.process');
    Route::get('/pengajuan-penyewaan/{penyewaan}/return-document/preview', [PengelolaPenyewaanController::class, 'previewReturnDocument'])
        ->name('pengelola.penyewaan.return-document.preview');
    Route::get('/pengajuan-penyewaan/{penyewaan}/return-document/download', [PengelolaPenyewaanController::class, 'downloadReturnDocument'])
        ->name('pengelola.penyewaan.return-document.download');

    // ── Serah Terima Penyewaan ─────────────────────────────────────────
    Route::get('/pengajuan-penyewaan/{penyewaan}/handover', [SerahTerimaController::class, 'show'])
        ->name('pengelola.penyewaan.handover.show');
    Route::get('/pengajuan-penyewaan/{penyewaan}/handover/track', [SerahTerimaController::class, 'track'])
        ->name('pengelola.penyewaan.handover.track');
    Route::get('/pengajuan-penyewaan/{penyewaan}/handover/download', [SerahTerimaController::class, 'downloadDocument'])
        ->name('pengelola.penyewaan.handover.download');
    Route::post('/pengajuan-penyewaan/{penyewaan}/handover/status', [SerahTerimaController::class, 'updateStatus'])
        ->name('pengelola.penyewaan.handover.update');
    Route::post('/pengajuan-penyewaan/{penyewaan}/handover/delivery-info', [SerahTerimaController::class, 'updateDeliveryInfo'])
        ->name('pengelola.penyewaan.handover.delivery-info');
    Route::post('/pengajuan-penyewaan/{penyewaan}/handover/mark-shipped', [SerahTerimaController::class, 'markAsShipped'])
        ->name('pengelola.penyewaan.handover.mark-shipped');
    Route::post('/pengajuan-penyewaan/{penyewaan}/handover/validate', [SerahTerimaController::class, 'validateHandover'])
        ->name('pengelola.penyewaan.handover.validate');
    Route::post('/pengajuan-penyewaan/{penyewaan}/handover/decide-damage', [SerahTerimaController::class, 'decideDamage'])
        ->name('pengelola.penyewaan.handover.decide-damage');
    Route::post('/pengajuan-penyewaan/{penyewaan}/handover/mark-returning', [SerahTerimaController::class, 'markAsReturning'])
        ->name('pengelola.penyewaan.handover.mark-returning');
    Route::get('/pengajuan-penyewaan/{penyewaan}/handover/return', [SerahTerimaController::class, 'showReturnForm'])
        ->name('pengelola.penyewaan.handover.return-form');
    Route::post('/pengajuan-penyewaan/{penyewaan}/handover/return', [SerahTerimaController::class, 'processReturn'])
        ->name('pengelola.penyewaan.handover.process-return');
    Route::get('/pengajuan-penyewaan/{penyewaan}/handover/download-return', [SerahTerimaController::class, 'downloadReturnDocument'])
        ->name('pengelola.penyewaan.handover.download-return');
    Route::post('/pengajuan-penyewaan/{penyewaan}/handover/confirm-collection-returned', [SerahTerimaController::class, 'confirmCollectionReturned'])
        ->name('pengelola.penyewaan.handover.confirm-collection-returned');
    Route::post('/pengajuan-penyewaan/{penyewaan}/handover/confirm-collection-arrived', [SerahTerimaController::class, 'confirmCollectionArrived'])
        ->name('pengelola.penyewaan.handover.confirm-collection-arrived');
    Route::post('/pengajuan-penyewaan/{penyewaan}/handover/store-refund-proof', [SerahTerimaController::class, 'storeRefundProof'])
        ->name('pengelola.penyewaan.handover.store-refund-proof');
    Route::post('/pengajuan-penyewaan/{penyewaan}/handover/confirm-rental-completed', [SerahTerimaController::class, 'confirmRentalCompleted'])
        ->name('pengelola.penyewaan.handover.confirm-rental-completed');
    Route::get('/pengajuan-penyewaan/{penyewaan}/handover/tracking-data', [SerahTerimaController::class, 'trackingData'])
        ->name('pengelola.penyewaan.handover.tracking-data');
    Route::post('/pengajuan-penyewaan/{penyewaan}/handover/manager-status', [SerahTerimaController::class, 'managerStatus'])
        ->name('pengelola.penyewaan.handover.manager-status');

    // ── Tickets ────────────────────────────────────────────────────────
    Route::resource('/tickets', TicketController::class);
    Route::prefix('/tickets/{ticket}')->group(function () {
        Route::get('/exceptions', [TicketController::class, 'manageExceptions'])->name('tickets.exceptions');
        Route::post('/exceptions', [TicketController::class, 'storeException'])->name('tickets.exceptions.store');
        Route::delete('/exceptions/{exception}', [TicketController::class, 'destroyException'])->name('tickets.exceptions.destroy');

        Route::get('/holidays', [TicketController::class, 'manageHolidays'])->name('tickets.holidays');
        Route::post('/sync-holidays', [TicketController::class, 'syncHolidays'])->name('tickets.sync-holidays');
        Route::post('/regenerate-all-quotas', [TicketController::class, 'regenerateAllQuotas'])->name('tickets.regenerate-all');
        Route::patch('/regenerate-quota', [TicketController::class, 'regenerateQuota'])->name('tickets.regenerate-quota');
        Route::get('/preview-holidays', [TicketController::class, 'previewHolidays'])->name('tickets.preview-holidays');

        // Manage quotas
        Route::get('/quotas', [TicketController::class, 'manageQuotas'])->name('tickets.quotas');
        Route::patch('/quotas/{quota}', [TicketController::class, 'updateQuota'])->name('tickets.quotas.update');
    });

    Route::resource('koleksi', KoleksiController::class);
    Route::post('/koleksi/categories', [KoleksiController::class, 'storeCategory'])->name('koleksi.categories.store');

    Route::get('/kondisi', [KondisiKoleksiController::class, 'index'])->name('kondisi.index');

    Route::get('/koleksi-konservasi', [KoleksiController::class, 'konservasiList'])->name('koleksi.konservasi-list');
    Route::get('/koleksi/{koleksi}/konservasi', [KoleksiController::class, 'konservasi'])->name('koleksi.konservasi');

    Route::prefix('koleksi/{koleksi}/kondisi')->name('koleksi.kondisi.')->group(function () {
        Route::get('create',         [KondisiKoleksiController::class, 'create'])->name('create');
        Route::post('/',             [KondisiKoleksiController::class, 'store'])->name('store');
        Route::get('{kondisi}',      [KondisiKoleksiController::class, 'show'])->name('show');
        Route::get('{kondisi}/edit', [KondisiKoleksiController::class, 'edit'])->name('edit');
        Route::put('{kondisi}',      [KondisiKoleksiController::class, 'update'])->name('update');
        Route::delete('{kondisi}',   [KondisiKoleksiController::class, 'destroy'])->name('destroy');
    }); 

    foreach (['perawatan', 'jadwal-perawatan'] as $legacyPrefix) {
        Route::any("/{$legacyPrefix}/{path?}", function (?string $path = null) {
            $target = '/jadwal-konservasi' . ($path ? '/' . $path : '');
            if (request()->getQueryString()) {
                $target .= '?' . request()->getQueryString();
            }

            return redirect($target, request()->isMethod('GET') ? 301 : 307);
        })->where('path', '.*');
    }

    // ── Jadwal Konservasi (Perawatan Koleksi) ─────────────────────
    Route::prefix('jadwal-konservasi')->name('jadwal-konservasi.')->group(function () {
        Route::get('/',                      [PerawatanKoleksiController::class, 'index'])->name('index');
        Route::get('/create',                [PerawatanKoleksiController::class, 'create'])->name('create');
        Route::post('/',                     [PerawatanKoleksiController::class, 'store'])->name('store');
        Route::get('/{perawatan}/edit',      [PerawatanKoleksiController::class, 'edit'])->name('edit');
        Route::get('/{perawatan}',           [PerawatanKoleksiController::class, 'show'])->name('show');
        Route::put('/{perawatan}',           [PerawatanKoleksiController::class, 'update'])->name('update');
        Route::post('/{perawatan}/selesai',  [PerawatanKoleksiController::class, 'selesai'])->name('selesai');
        Route::post('/{perawatan}/batalkan', [PerawatanKoleksiController::class, 'batalkan'])->name('batalkan');
        Route::delete('/{perawatan}',        [PerawatanKoleksiController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('konservasi')->name('konservasi.')->group(function () {
        Route::prefix('tindakan')->name('tindakan.')->group(function () {
            Route::get('/', [ConservationActionController::class, 'index'])->name('index');
            Route::post('/', [ConservationActionController::class, 'store'])->name('store');
            Route::get('/{action}', [ConservationActionController::class, 'show'])->name('show');
            Route::get('/{action}/rencana', [ConservationActionController::class, 'plan'])->name('plan');
            Route::post('/{action}/rencana', [ConservationActionController::class, 'storePlan'])->name('plan.store');
            Route::get('/{action}/pelaksanaan', [ConservationActionController::class, 'pelaksanaan'])->name('pelaksanaan');
            Route::post('/{action}/pelaksanaan', [ConservationActionController::class, 'storeImplementation'])->name('pelaksanaan.store');
            Route::put('/{action}/pelaksanaan', [ConservationActionController::class, 'updateImplementation'])->name('pelaksanaan.update');
            Route::get('/{action}/hasil', [ConservationActionController::class, 'hasil'])->name('hasil');
            Route::post('/{action}/hasil', [ConservationActionController::class, 'storeResult'])->name('hasil.store');
        });
    });

    // ── Koleksi ────────────────────────────────────────────────────────
    Route::resource('koleksi', KoleksiController::class);
    Route::prefix('koleksi/{koleksi}/kondisi')->name('koleksi.kondisi.')->group(function () {
        Route::get('create', [KondisiKoleksiController::class, 'create'])->name('create');
        Route::post('/', [KondisiKoleksiController::class, 'store'])->name('store');
        Route::get('{kondisi}/edit', [KondisiKoleksiController::class, 'edit'])->name('edit');
        Route::put('{kondisi}', [KondisiKoleksiController::class, 'update'])->name('update');
        Route::delete('{kondisi}', [KondisiKoleksiController::class, 'destroy'])->name('destroy');
    });

    // ── Deposit & Pemeriksaan Akhir ────────────────────────────────────
    Route::get('/pengajuan-penyewaan/{penyewaan}/deposit/final-inspection', [DepositController::class, 'showFinalInspection'])
        ->name('pengelola.deposit.final-inspection');
    Route::post('/pengajuan-penyewaan/{penyewaan}/deposit/final-inspection', [DepositController::class, 'storeFinalInspection'])
        ->name('pengelola.deposit.store-final-inspection');
    Route::get('/pengajuan-penyewaan/{penyewaan}/deposit', [DepositController::class, 'show'])
        ->name('pengelola.deposit.show');
    Route::post('/pengajuan-penyewaan/{penyewaan}/deposit/refund', [DepositController::class, 'storeRefund'])
        ->name('pengelola.deposit.refund');
    Route::post('/pengajuan-penyewaan/{penyewaan}/deposit/damage-invoice', [DepositController::class, 'storeDamageInvoice'])
        ->name('pengelola.deposit.damage-invoice');

}); // ← tutup group ['auth', 'role:pengelola']


// ── Pembelian (Pengelola) ──────────────────────────────────────────────
Route::middleware(['auth', 'role:pengelola'])
    ->prefix('pengelola/pembelian')
    ->name('pengelola.pembelian.')
    ->group(function () {
        Route::get('/', [PengelolaPembelianController::class, 'index'])->name('index');
        Route::get('/transactions', [PengelolaTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{type}/{id}', [PengelolaTransactionController::class, 'show'])->name('transactions.show');
        Route::get('/riwayat', [PengelolaPembelianController::class, 'riwayat'])->name('riwayat');
        Route::get('/{pembelian}/invoice', [PembelianController::class, 'downloadInvoice'])->name('invoice'); // ← ubah ini
        Route::get('/{pembelian}', [PengelolaPembelianController::class, 'show'])->name('show');
        Route::post('/{pembelian}/setujui', [PengelolaPembelianController::class, 'approve'])->name('approve');
        Route::post('/{pembelian}/tolak', [PengelolaPembelianController::class, 'reject'])->name('reject');
        Route::get('/{pembelian}/tracking', [SerahTerimaPembelianController::class, 'tracking'])->name('tracking');
        // Serah Terima Pembelian
        Route::get('/{pembelian}/serah-terima', [SerahTerimaPembelianController::class, 'show'])
            ->name('serah-terima');
        Route::get('/{pembelian}/serah-terima/tracking-data', [SerahTerimaPembelianController::class, 'trackingData'])->name('serah-terima.tracking-data');
        Route::post('/{pembelian}/serah-terima/validate', [SerahTerimaPembelianController::class, 'validateDocument'])
            ->name('serah-terima.validate');
        Route::get('/{pembelian}/serah-terima/uploaded/preview', [SerahTerimaPembelianController::class, 'previewUploadedDocument'])->name('serah-terima.uploaded.preview');
        Route::get('/{pembelian}/serah-terima/uploaded/download', [SerahTerimaPembelianController::class, 'downloadUploadedDocument'])->name('serah-terima.uploaded.download');
        Route::get('/{pembelian}/serah-terima/certificate/download', [SerahTerimaPembelianController::class, 'downloadCertificate'])->name('serah-terima.certificate.download');
        Route::get('/{pembelian}/serah-terima/certificate/preview', [SerahTerimaPembelianController::class, 'previewCertificate'])->name('serah-terima.certificate.preview');
        Route::post('/{pembelian}/serah-terima/delivery-info', [SerahTerimaPembelianController::class, 'updateDeliveryInfo'])
            ->name('serah-terima.delivery-info');
        Route::post('/{pembelian}/serah-terima/mark-shipped', [SerahTerimaPembelianController::class, 'markAsShipped'])
            ->name('serah-terima.mark-shipped');
        Route::post('/{pembelian}/serah-terima/complete', [SerahTerimaPembelianController::class, 'markAsCompleted'])
            ->name('serah-terima.complete');
        Route::post('/{pembelian}/serah-terima/manager-status', [SerahTerimaPembelianController::class, 'updateManagerDeliveryStatus'])->name('serah-terima.manager-status');
        Route::post('/{pembelian}/serah-terima/decide-damage', [SerahTerimaPembelianController::class, 'decideDamage'])->name('serah-terima.decide-damage');
        Route::post('/{pembelian}/serah-terima/store-refund-proof', [SerahTerimaPembelianController::class, 'storeRefundProof'])->name('serah-terima.store-refund-proof');
        Route::post('/{pembelian}/serah-terima/confirm-collection-arrived', [SerahTerimaPembelianController::class, 'confirmCollectionArrived'])->name('serah-terima.confirm-collection-arrived');
    });

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifikasi/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifikasi/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
});

/*
|--------------------------------------------------------------------------
| Pengguna
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:pengguna'])->group(function () {

    Route::get('/pilihtiket', [TicketController::class, 'userIndex'])->name('tiket.index');
    Route::get('/pilihtiket/{id}', [TicketController::class, 'userShow'])->name('tiket.show');
    Route::post('/pilihtiket/{id}/checkout', [PemesananTiketController::class, 'checkout'])->name('tiket.checkout');

    // Pemesanan Tiket
    Route::get('/pemesanan-tiket', [PemesananTiketController::class, 'index'])->name('pemesanan-tiket.index');
    Route::get('/pemesanan-tiket/{pemesananTiket}', [PemesananTiketController::class, 'show'])->name('pemesanan-tiket.show');
    Route::get('/pemesanan-tiket/{pemesananTiket}/detail-pengunjung', [PemesananTiketController::class, 'detailPengunjung'])->name('pemesanan-tiket.detail-pengunjung');
    Route::post('/pemesanan-tiket/{pemesananTiket}/detail-pengunjung', [PemesananTiketController::class, 'storeDetailPengunjung'])->name('pemesanan-tiket.store-detail-pengunjung');
    Route::get('/pemesanan-tiket/{pemesananTiket}/bayar', [PemesananTiketController::class, 'bayar'])->name('pemesanan-tiket.bayar');
    Route::post('/pemesanan-tiket/{pemesananTiket}/midtrans/snap-token', [PemesananTiketController::class, 'midtransSnapToken'])->name('pemesanan-tiket.midtrans.snap-token');
    Route::post('/pemesanan-tiket/{pemesananTiket}/midtrans/sync-status', [PemesananTiketController::class, 'midtransSyncStatus'])->name('pemesanan-tiket.midtrans.sync-status');
    Route::get('/pemesanan-tiket/{pemesananTiket}/e-tiket', [PemesananTiketController::class, 'etiket'])->name('pemesanan-tiket.e-tiket');
    Route::post('/pemesanan-tiket/{pemesananTiket}/upload-bukti', [PemesananTiketController::class, 'uploadBukti'])->name('pemesanan-tiket.uploadBukti');
    Route::get('/pemesanan-tiket/{pemesananTiket}/reschedule', [PemesananTiketController::class, 'formReschedule'])->name('pemesanan-tiket.reschedule');
    Route::post('/pemesanan-tiket/{pemesananTiket}/reschedule', [PemesananTiketController::class, 'reschedule'])->name('pemesanan-tiket.reschedule.store');
    Route::get('/pemesanan-tiket/{pemesananTiket}/batalkan', [PemesananTiketController::class, 'batalkanForm'])->name('pemesanan-tiket.batalkan.form');
    Route::post('/pemesanan-tiket/{pemesananTiket}/batalkan', [PemesananTiketController::class, 'batalkan'])->name('pemesanan-tiket.batalkan');
    
    // E-tiket per detail pengunjung
    Route::get('/pemesanan-tiket/{pemesananTiket}/semua-e-tiket', [PemesananTiketController::class, 'semuaEtiket'])->name('pemesanan-tiket.semua-e-tiket');
    Route::get('/pemesanan-tiket/{pemesananTiket}/e-tiket/{detailPengunjung}', [PemesananTiketController::class, 'etiket'])->name('pemesanan-tiket.e-tiket.detail');

    // ── Penyewaan ──────────────────────────────────────────────────────
    Route::get('/penyewaan', [PenyewaanController::class, 'index'])->name('penyewaan.index');
    Route::get('/penyewaan/riwayat', [PenyewaanController::class, 'riwayat'])->name('penyewaan.riwayat');
    Route::get('/penyewaan/requests', [PenyewaanController::class, 'requests'])->name('penyewaan.requests');
    Route::get('/penyewaan/requests/{penyewaan}', [PenyewaanController::class, 'show'])->name('penyewaan.requests.show');
    Route::get('/penyewaan/requests/{penyewaan}/edit', [PenyewaanController::class, 'edit'])->name('penyewaan.requests.edit');
    Route::put('/penyewaan/requests/{penyewaan}', [PenyewaanController::class, 'update'])->name('penyewaan.requests.update');
    Route::post('/penyewaan/requests/{penyewaan}/cancel', [PenyewaanController::class, 'cancel'])->name('penyewaan.requests.cancel');
    Route::post('/penyewaan/requests/{penyewaan}/delete', [PenyewaanController::class, 'destroy'])->name('penyewaan.requests.destroy');
    Route::get('/penyewaan/requests/{penyewaan}/agreement/download', [PenyewaanController::class, 'downloadAgreement'])
        ->name('penyewaan.requests.agreement.download');
    Route::get('/penyewaan/requests/{penyewaan}/invoice/download', [PenyewaanController::class, 'downloadInvoice'])
        ->name('penyewaan.requests.invoice.download');
    Route::post('/penyewaan/requests/{penyewaan}/signed-agreement', [PenyewaanController::class, 'uploadSignedAgreement'])
        ->name('penyewaan.requests.signedAgreement.upload');
    Route::get('/penyewaan/requests/{penyewaan}/payment', [PenyewaanController::class, 'showPayment'])
        ->name('penyewaan.requests.payment');
    Route::post('/penyewaan/requests/{penyewaan}/payment', [PenyewaanController::class, 'processPayment'])
        ->name('penyewaan.requests.payment.process');
    Route::get('/penyewaan/requests/{penyewaan}/payment/gateway', [PenyewaanController::class, 'paymentGateway'])
        ->name('penyewaan.requests.payment.gateway');
    Route::get('/penyewaan/requests/{penyewaan}/payment/check', [PenyewaanController::class, 'checkPaymentStatus'])
        ->name('penyewaan.requests.payment.check');
    Route::get('/penyewaan/requests/{penyewaan}/payment/success', [PenyewaanController::class, 'paymentSuccess'])
        ->name('penyewaan.requests.payment.success');
    Route::get('/penyewaan/requests/{penyewaan}/payment/failed', [PenyewaanController::class, 'paymentFailed'])
        ->name('penyewaan.requests.payment.failed');
    Route::get('/penyewaan/requests/{penyewaan}/payments', [PenyewaanController::class, 'paymentHistory'])
        ->name('penyewaan.requests.payment.history');
    Route::get('/penyewaan/requests/{penyewaan}/payment-status', [PenyewaanController::class, 'paymentStatus'])
        ->name('penyewaan.requests.payment.status');

    // ── Serah Terima Penyewaan (Pengguna) ──────────────────────────────
    Route::post('/penyewaan/requests/{penyewaan}/handover/confirm-received', [SerahTerimaController::class, 'confirmReceived'])
        ->name('penyewaan.requests.handover.confirm-received');
    Route::get('/penyewaan/requests/{penyewaan}/handover/condition-check', [SerahTerimaController::class, 'showConditionCheck'])
    ->name('penyewaan.requests.handover.condition-check');
    Route::post('/penyewaan/requests/{penyewaan}/handover/condition-good', [SerahTerimaController::class, 'submitConditionGood'])
        ->name('penyewaan.requests.handover.condition-good');
    Route::post('/penyewaan/requests/{penyewaan}/handover/condition-damage', [SerahTerimaController::class, 'submitConditionDamage'])
        ->name('penyewaan.requests.handover.condition-damage');
    Route::post('/penyewaan/requests/{penyewaan}/handover/data-rekening', [SerahTerimaController::class, 'submitBankAccount'])
        ->name('penyewaan.requests.handover.submit-bank-account');
    Route::get('/penyewaan/requests/{penyewaan}/handover', [SerahTerimaController::class, 'show'])
        ->name('penyewaan.requests.handover.show');
    Route::get('/penyewaan/requests/{penyewaan}/handover/track', [SerahTerimaController::class, 'track'])
        ->name('penyewaan.requests.handover.track');
    Route::get('/penyewaan/requests/{penyewaan}/handover/download', [SerahTerimaController::class, 'downloadDocument'])
        ->name('penyewaan.requests.handover.download');
    Route::get('/penyewaan/requests/{penyewaan}/handover/upload', [SerahTerimaController::class, 'showUploadForm'])
        ->name('penyewaan.requests.handover.upload.form');
    Route::post('/penyewaan/requests/{penyewaan}/handover/upload', [SerahTerimaController::class, 'uploadDocument'])
        ->name('penyewaan.requests.handover.upload');
    Route::get('/penyewaan/requests/{penyewaan}/handover/download-return', [SerahTerimaController::class, 'downloadReturnDocument'])
        ->name('penyewaan.requests.handover.download-return');
    Route::post('/penyewaan/requests/{penyewaan}/handover/submit-return-shipment', [SerahTerimaController::class, 'submitReturnShipment'])
        ->name('penyewaan.requests.handover.submit-return-shipment');
    Route::post('/penyewaan/requests/{penyewaan}/handover/return-status', [SerahTerimaController::class, 'returnShipmentStatus'])
        ->name('penyewaan.requests.handover.return-status');
    Route::post('/penyewaan/requests/{penyewaan}/handover/upload-signed-return', [SerahTerimaController::class, 'uploadSignedReturnDocument'])
        ->name('penyewaan.requests.handover.upload-signed-return');
    Route::post('/penyewaan/requests/{penyewaan}/handover/confirm-refund', [SerahTerimaController::class, 'confirmRefund'])
        ->name('penyewaan.requests.handover.confirm-refund');
    Route::post('/penyewaan/requests/{penyewaan}/handover/mark-return-shipped', [SerahTerimaController::class, 'markReturnShipped'])
        ->name('penyewaan.requests.handover.mark-return-shipped');
    Route::get('/penyewaan/requests/{penyewaan}/handover/download-initial-return', [SerahTerimaController::class, 'downloadInitialReturnDocument'])
        ->name('penyewaan.requests.handover.download-initial-return');
    Route::post('/penyewaan/requests/{penyewaan}/handover/upload-signed-initial-return', [SerahTerimaController::class, 'uploadSignedInitialReturnDocument'])
        ->name('penyewaan.requests.handover.upload-signed-initial-return');
    Route::get('/penyewaan/requests/{penyewaan}/handover/tracking-data', [SerahTerimaController::class, 'trackingData'])
        ->name('penyewaan.requests.handover.tracking-data');

    // ── Deposit (Pengguna) ─────────────────────────────────────────────
    Route::get('/penyewaan/requests/{penyewaan}/deposit', [DepositController::class, 'showTenant'])
        ->name('penyewaan.requests.deposit.show');
    Route::get('/penyewaan/requests/{penyewaan}/deposit/damage-payment', [DepositController::class, 'showDamagePayment'])
        ->name('penyewaan.requests.deposit.damage-payment');

    // ── Step Penyewaan ─────────────────────────────────────────────────
    Route::get('/penyewaan/{koleksi}/step/1', [PenyewaanController::class, 'step1'])->name('penyewaan.step1');
    Route::post('/penyewaan/{koleksi}/step/1', [PenyewaanController::class, 'storeStep1'])->name('penyewaan.storeStep1');
    Route::get('/penyewaan/{koleksi}/step/2', [PenyewaanController::class, 'step2'])->name('penyewaan.step2');
    Route::post('/penyewaan/{koleksi}/step/2', [PenyewaanController::class, 'storeStep2'])->name('penyewaan.storeStep2');
    Route::get('/penyewaan/{koleksi}/step/3', [PenyewaanController::class, 'step3'])->name('penyewaan.step3');
    Route::post('/penyewaan/{koleksi}/step/3', [PenyewaanController::class, 'storeStep3'])->name('penyewaan.storeStep3');
    Route::get('/penyewaan/{koleksi}/step/4', [PenyewaanController::class, 'step4'])->name('penyewaan.step4');
    Route::post('/penyewaan/{koleksi}/step/4', [PenyewaanController::class, 'storeStep4'])->name('penyewaan.storeStep4');
    Route::get('/penyewaan/{koleksi}/step/5', [PenyewaanController::class, 'step5'])->name('penyewaan.step5');
    Route::get('/penyewaan/{koleksi}/step/6', [PenyewaanController::class, 'step6'])->name('penyewaan.step6');
    Route::get('/penyewaan/{koleksi}/create', [PenyewaanController::class, 'create'])->name('penyewaan.create');
    Route::get('/penyewaan/{koleksi}', [PenyewaanController::class, 'showPainting'])->name('penyewaan.show');
    Route::post('/penyewaan/{koleksi}', [PenyewaanController::class, 'store'])->name('penyewaan.store');

    // ── Pembelian (Pengguna) ───────────────────────────────────────────
    Route::prefix('pembelian')->name('pembelian.')->group(function () {
        Route::get('/{koleksi}/step/1', [PembelianController::class, 'step1'])->name('step1');
        Route::post('/{koleksi}/step/1', [PembelianController::class, 'storeStep1'])->name('storeStep1');
        Route::get('/{koleksi}/step/2', [PembelianController::class, 'step2'])->name('step2');
        Route::get('/', [PembelianController::class, 'index'])->name('index');
        Route::get('/riwayat', [PembelianController::class, 'riwayat'])->name('riwayat');
        Route::get('/{koleksi}/buat', [PembelianController::class, 'create'])->name('create');
        Route::post('/{koleksi}', [PembelianController::class, 'store'])->name('store');
        Route::get('/{pembelian}/invoice', [PembelianController::class, 'downloadInvoice'])->name('invoice'); // ← tambah ini
        Route::get('/{pembelian}', [PembelianController::class, 'show'])->name('show');
        Route::post('/{pembelian}/batalkan', [PembelianController::class, 'cancel'])->name('cancel');
        Route::get('/{pembelian}/bayar', [PembelianController::class, 'showPayment'])->name('payment');
        Route::post('/{pembelian}/bayar/proses', [PembelianController::class, 'processPayment'])->name('payment.process');
        Route::get('/{pembelian}/bayar/berhasil', [PembelianController::class, 'paymentSuccess'])->name('payment.success');
        Route::get('/{pembelian}/bayar/gagal', [PembelianController::class, 'paymentFailed'])->name('payment.failed');
        Route::post('/{pembelian}/cancel-pending', [PembelianController::class, 'cancelPendingPayment'])->name('cancelPendingPayment');
        Route::get('/{pembelian}/serah-terima', [SerahTerimaPembelianController::class, 'show'])->name('serah-terima');
        Route::get('/{pembelian}/serah-terima/tracking-data', [SerahTerimaPembelianController::class, 'trackingData'])->name('serah-terima.tracking-data');
        Route::get('/{pembelian}/serah-terima/download', [SerahTerimaPembelianController::class, 'downloadDocument'])->name('serah-terima.download');
        Route::get('/{pembelian}/serah-terima/uploaded/preview', [SerahTerimaPembelianController::class, 'previewUploadedDocument'])->name('serah-terima.uploaded.preview');
        Route::get('/{pembelian}/serah-terima/uploaded/download', [SerahTerimaPembelianController::class, 'downloadUploadedDocument'])->name('serah-terima.uploaded.download');
        Route::get('/{pembelian}/serah-terima/certificate/download', [SerahTerimaPembelianController::class, 'downloadCertificate'])->name('serah-terima.certificate.download');
        Route::get('/{pembelian}/serah-terima/certificate/preview', [SerahTerimaPembelianController::class, 'previewCertificate'])->name('serah-terima.certificate.preview');
        Route::post('/{pembelian}/serah-terima/upload', [SerahTerimaPembelianController::class, 'uploadDocument'])->name('serah-terima.upload');
        Route::post('/{pembelian}/serah-terima/validate', [SerahTerimaPembelianController::class, 'validateDocument'])->name('serah-terima.validate');
        Route::post('/{pembelian}/konfirmasi-terima', [SerahTerimaPembelianController::class, 'confirmReceived'])->name('confirm-received');
        Route::get('/{pembelian}/cek-kondisi', [SerahTerimaPembelianController::class, 'showConditionCheck'])->name('condition-check');
        Route::post('/{pembelian}/cek-kondisi/baik', [SerahTerimaPembelianController::class, 'submitConditionGood'])->name('condition-good');
        Route::post('/{pembelian}/cek-kondisi/kerusakan', [SerahTerimaPembelianController::class, 'submitConditionDamage'])->name('condition-damage');
        Route::post('/{pembelian}/data-rekening', [SerahTerimaPembelianController::class, 'submitBankAccount'])->name('submit-bank-account');
        Route::post('/{pembelian}/return-status', [SerahTerimaPembelianController::class, 'returnShipmentStatus'])->name('return-status');
        Route::post('/{pembelian}/confirm-refund-received', [SerahTerimaPembelianController::class, 'confirmRefundReceived'])->name('confirm-refund-received');
        Route::get('/{pembelian}/tracking', [SerahTerimaPembelianController::class, 'tracking'])->name('tracking');
    });

    // ── Upload Return Document (Pengguna) ──────────────────────────────
    Route::post('/penyewaan/{penyewaan}/upload-return-document', [PenyewaanController::class, 'uploadReturnDocument'])
        ->name('penyewaan.upload-return-document');
});

/*
|--------------------------------------------------------------------------
| Midtrans Webhook (tanpa auth)
|--------------------------------------------------------------------------
*/

Route::post('/midtrans/webhook', [App\Http\Controllers\PaymentController::class, 'callback'])
    ->name('midtrans.webhook')
    ->withoutMiddleware('auth');

Route::post('/midtrans/webhook/pembelian', [PembelianController::class, 'webhook'])
    ->name('pembelian.webhook')
    ->withoutMiddleware('auth');

Route::post('/midtrans/webhook/damage-invoice', [DepositController::class, 'handleDamagePaymentWebhook'])
    ->name('damage.invoice.webhook')
    ->withoutMiddleware(['auth', 'web']);

Route::prefix('api/rajaongkir')->group(function () {
    Route::get('provinces', [\App\Http\Controllers\RajaOngkirController::class, 'provinces']);
    Route::get('cities', [\App\Http\Controllers\RajaOngkirController::class, 'cities']);
    Route::get('find-city', [\App\Http\Controllers\RajaOngkirController::class, 'findCity']); // ← tambah ini
    Route::post('cost', [\App\Http\Controllers\RajaOngkirController::class, 'cost']);
});

Route::prefix('api/binderbyte')->group(function () {
    Route::get('provinces', [\App\Http\Controllers\BinderbyteController::class, 'provinces']);
    Route::get('cities', [\App\Http\Controllers\BinderbyteController::class, 'cities']);
    Route::post('cost', [\App\Http\Controllers\BinderbyteController::class, 'cost']);
});

/*
|--------------------------------------------------------------------------
| Verifikasi Sertifikat (Publik — tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/verify-certificate/{certId}', [CertificateController::class, 'verify'])
    ->name('certificate.verify')
    ->withoutMiddleware(['auth', 'web']);

// ── Wilayah (emsifa proxy) ─────────────────────────────────────────────
Route::prefix('api/wilayah')->group(function () {
    Route::get('provinces', [\App\Http\Controllers\WilayahController::class, 'provinces']);
    Route::get('regencies/{provinceId}', [\App\Http\Controllers\WilayahController::class, 'regencies']);
    Route::get('districts/{cityId}', [\App\Http\Controllers\WilayahController::class, 'districts']);
    Route::get('villages/{districtId}', [\App\Http\Controllers\WilayahController::class, 'villages']); // ← BARU
});

require __DIR__ . '/auth.php';