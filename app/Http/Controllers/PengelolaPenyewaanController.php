<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Penyewaan;
use App\Models\ShippingZone;
use App\Notifications\PenyewaanStatusNotification;
use App\Services\DocumentService;
use App\Services\MidtransService;
use App\Services\ShippingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengelolaPenyewaanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);

        $activeStatuses = Penyewaan::ACTIVE_STATUSES;

        $query = Penyewaan::with(['painting', 'user'])
            ->whereIn('status', $activeStatuses);
    
        if ($search = trim($request->get('search', ''))) {
            $query->where(function ($sub) use ($search) {
                $sub->where('id', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('nama_instansi', 'like', "%{$search}%")
                    ->orWhereHas('painting', function ($painting) use ($search) {
                        $painting->where('title', 'like', "%{$search}%")
                                 ->orWhere('artist', 'like', "%{$search}%");
                    });
            });
        }

        $statusFilters = [
            'menunggu_verifikasi'           => fn ($q) => $q->where('status', 'menunggu_verifikasi'),
            'menunggu_dokumen_perjanjian'   => fn ($q) => $q->where('status', 'menunggu_dokumen_perjanjian'),
            'verifikasi_dokumen_perjanjian' => fn ($q) => $q->where('status', 'verifikasi_dokumen_perjanjian'),
            'menunggu_pembayaran'           => fn ($q) => $q->where('status', 'menunggu_pembayaran'),
            'pengiriman'                    => fn ($q) => $q->where('status', 'pengiriman'),
            'siap_diserahkan'               => fn ($q) => $q->where('status', 'siap_diserahkan'),
            'dalam_pengiriman'              => fn ($q) => $q->where('status', 'dalam_pengiriman'),
            'pengecekan_kondisi'            => fn ($q) => $q->where('status', 'pengecekan_kondisi'),
            'menunggu_review_kerusakan'     => fn ($q) => $q->where('status', 'menunggu_review_kerusakan'),
            'menunggu_data_rekening'        => fn ($q) => $q->where('status', 'menunggu_data_rekening'),
            'menunggu_penerimaan_koleksi'   => fn ($q) => $q->where('status', 'menunggu_penerimaan_koleksi'),
            'menunggu_refund_kerusakan'     => fn ($q) => $q->where('status', 'menunggu_refund_kerusakan'),
            'menunggu_konfirmasi_refund'    => fn ($q) => $q->where('status', 'menunggu_konfirmasi_refund'),
            'menunggu_dokumen_serah_terima' => fn ($q) => $q->where('status', 'menunggu_dokumen_serah_terima'),
            'verifikasi_serah_terima'       => fn ($q) => $q->where('status', 'verifikasi_serah_terima'),
            'aktif'                         => fn ($q) => $q->where('status', 'aktif'),
            'active'                        => fn ($q) => $q->where('status', 'aktif'),
            'pengembalian'                  => fn ($q) => $q->where('status', 'pengembalian'),
            'menunggu_ttd_pengembalian'     => fn ($q) => $q->where('status', 'menunggu_ttd_pengembalian'),
            'menunggu_pembayaran_kerusakan' => fn ($q) => $q->where('status', 'menunggu_pembayaran_kerusakan'),
            'menunggu_konfirmasi_selesai'   => fn ($q) => $q->where('status', 'menunggu_konfirmasi_selesai'),
            'selesai'                       => fn ($q) => $q->where('status', 'selesai'),
            'completed'                     => fn ($q) => $q->where('status', 'selesai'),
            'ditolak'                       => fn ($q) => $q->where('status', 'ditolak'),
            'dibatalkan'                    => fn ($q) => $q->where('status', 'dibatalkan'),
        ];

        if ($status = $request->get('status')) {
            if (isset($statusFilters[$status])) {
                $statusFilters[$status]($query);
            }
        }

        if ($jenis = $request->get('jenis_penyewa')) {
            $query->where('rental_type', $jenis);
        }

        $requests = $query->latest()->paginate($perPage)->withQueryString();

        $requests->getCollection()->each(function (Penyewaan $penyewaan) {
            $penyewaan->loadMissing('serahTerima');
            $penyewaan->syncLegacyShippingStatus();
        });

        $today          = Carbon::today();
        $nearReturnDate = Carbon::today()->addDays(7);

        $totalAll                = Penyewaan::count();
        $totalMenungguVerifikasi = Penyewaan::where('status', 'menunggu_verifikasi')->count();
        $totalMenungguPerjanjian = Penyewaan::whereIn('status', ['menunggu_dokumen_perjanjian', 'verifikasi_dokumen_perjanjian'])->count();
        $totalMenungguPembayaran = Penyewaan::where('status', 'menunggu_pembayaran')->count();
        $totalPengiriman         = Penyewaan::whereIn('status', [
            'pengiriman', 'siap_diserahkan', 'dalam_pengiriman', 'pengecekan_kondisi',
            'menunggu_review_kerusakan', 'menunggu_data_rekening', 'menunggu_penerimaan_koleksi',
            'menunggu_refund_kerusakan',
        ])->count();
        $totalAktif              = Penyewaan::where('status', 'aktif')->count();
        $totalNearingReturn      = Penyewaan::where('status', 'aktif')
                                       ->whereBetween('end_date', [$today->toDateString(), $nearReturnDate->toDateString()])
                                       ->count();
        $totalCompleted          = Penyewaan::where('status', 'selesai')->count();
        $totalRejected           = Penyewaan::where('status', 'ditolak')->count();

        return view('pengelola.penyewaan.index', [
            'requests'                => $requests,
            'totalAll'                => $totalAll,
            'totalMenungguVerifikasi' => $totalMenungguVerifikasi,
            'totalMenungguPerjanjian' => $totalMenungguPerjanjian,
            'totalMenungguPembayaran' => $totalMenungguPembayaran,
            'totalPengiriman'         => $totalPengiriman,
            'totalAktif'              => $totalAktif,
            'totalNearingReturn'      => $totalNearingReturn,
            'totalCompleted'          => $totalCompleted,
            'totalRejected'           => $totalRejected,
            'totalDelivered'          => $totalPengiriman,
        ]);
    }

    public function riwayat(Request $request)
    {
        $filterStatus = $request->get('status');
        $filterDari   = $request->get('dari');
        $filterSampai = $request->get('sampai');
        $filterSearch = $request->get('search');
        $perPage      = (int) $request->get('per_page', 20);

        $query = Penyewaan::with(['painting', 'user'])
            ->whereIn('status', ['selesai', 'ditolak', 'dibatalkan'])
            ->latest();

        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }

        if ($filterDari) {
            $query->whereDate('created_at', '>=', $filterDari);
        }

        if ($filterSampai) {
            $query->whereDate('created_at', '<=', $filterSampai);
        }

        if ($filterSearch) {
            $query->where(function ($q) use ($filterSearch) {
                $q->where('contact_name', 'like', "%{$filterSearch}%")
                ->orWhere('nama_instansi', 'like', "%{$filterSearch}%")
                ->orWhereHas('painting', fn($p) =>
                    $p->where('title', 'like', "%{$filterSearch}%")
                        ->orWhere('artist', 'like', "%{$filterSearch}%")
                );
            });
        }

        $riwayat = $query->paginate($perPage)->withQueryString();

        $counts = [
            'selesai'    => Penyewaan::where('status', 'selesai')->count(),
            'ditolak'    => Penyewaan::where('status', 'ditolak')->count(),
            'dibatalkan' => Penyewaan::where('status', 'dibatalkan')->count(),
        ];

        return view('pengelola.penyewaan.riwayat', compact('riwayat', 'counts'));
    }

    public function show(Penyewaan $penyewaan, MidtransService $midtrans, ShippingService $shippingService)
    {
        $this->syncPaymentStatus($penyewaan, $midtrans);
        $penyewaan->loadMissing(['serahTerima', 'shippingZone']);
        $penyewaan->syncLegacyShippingStatus();
        $penyewaan->refresh();

        $isPendingVerification = $penyewaan->status === 'menunggu_verifikasi';
        $isAwaitingPayment     = in_array($penyewaan->status, [
            'menunggu_dokumen_perjanjian',
            'verifikasi_dokumen_perjanjian',
            'menunggu_pembayaran',
        ]);

        // ── Fallback: isi destination_city_id jika kosong ────────────────
        if ($isPendingVerification && ! $penyewaan->destination_city_id) {
            $cityName = $penyewaan->city_name
                ?: strtolower(preg_replace('/^(kab\.|kab |kota |kabupaten |kota)\s*/i', '', $penyewaan->kota_kabupaten ?? ''));

            if ($cityName) {
                try {
                    $response = \Illuminate\Support\Facades\Http::get(
                        config('app.url') . '/api/rajaongkir/find-city',
                        ['city_name' => $cityName, 'province_name' => $penyewaan->provinsi ?? '']
                    );
                    if ($response->ok() && $response->json('city_id')) {
                        $penyewaan->update(['destination_city_id' => $response->json('city_id')]);
                        $penyewaan->refresh();
                    }
                } catch (\Exception $e) {
                    \Log::warning('Fallback find-city gagal: ' . $e->getMessage());
                }
            }
        }

        // ── Info zona pengiriman untuk form verifikasi ───────────────────
        $zonaSummary = null;
        if ($isPendingVerification) {
            $zonaSummary = $shippingService->getZoneSummaryByProvince(
                $penyewaan->provinsi,
                $penyewaan->kota_kabupaten
            );
        }

        return view('pengelola.penyewaan.show', compact(
            'penyewaan',
            'isPendingVerification',
            'isAwaitingPayment',
            'zonaSummary'
        ));
    }

    // ── Setujui + tentukan metode & ongkir pengiriman ─────────────────
    //
    // Flow (mirip PengelolaPembelianController::approve):
    // 1. Pengelola pilih metode: 'courier' atau 'manager'
    // 2. Pengelola input / pilih ongkir
    // 3. Sistem hitung total = subtotal_sewa + deposit + ongkir
    // 4. Generate dokumen perjanjian & invoice dengan total final
    //
    public function approve(
        Request $request,
        Penyewaan $penyewaan,
        ShippingService $shippingService
    ): RedirectResponse {
        if ($penyewaan->status !== 'menunggu_verifikasi') {
            return redirect()->route('pengelola.penyewaan.show', $penyewaan)
                ->with('error', 'Pengajuan hanya bisa diverifikasi jika masih berstatus Menunggu Verifikasi.');
        }

        if ($penyewaan->agreement_document_path || $penyewaan->invoice_document_path) {
            return redirect()->route('pengelola.penyewaan.show', $penyewaan)
                ->with('error', 'Dokumen sudah pernah dibuat sebelumnya.');
        }

        $request->validate([
            'shipping_method_type' => ['required', 'in:courier,manager'],
            'courier_name'         => ['nullable', 'string', 'max:100'],
            'courier_service'      => ['nullable', 'string', 'max:100'],
            'courier_etd'          => ['nullable', 'string', 'max:50'],
            'shipping_cost'        => ['required', 'numeric', 'min:0'],
            'catatan_pengelola'    => ['nullable', 'string', 'max:2000'],
        ]);

        $methodType   = $request->input('shipping_method_type');
        $shippingCost = (float) $request->input('shipping_cost');

        // ── Resolve zona ─────────────────────────────────────────────
        $zone = $shippingService->resolveZone(
            $penyewaan->provinsi,
            $penyewaan->kota_kabupaten
        );

        // Zona gratis → ongkir selalu 0
        if ($zone && $zone->is_free) {
            $shippingCost = 0;
        }

        // ── Hitung total final ────────────────────────────────────────
        $durasi      = $penyewaan->duration_days;
        $hargaSewa   = $penyewaan->painting->daily_rate ?? $penyewaan->painting->rental_price ?? 0;
        $subtotal    = $hargaSewa * $durasi;
        $deposit     = $penyewaan->calculateDeposit();
        $totalBayar  = $subtotal + $deposit + (int) $shippingCost;

        // ── Simpan data pengiriman ke penyewaan ───────────────────────
        $penyewaan->update([
            'shipping_method_type' => $methodType,
            'courier_name'         => $methodType === 'courier' ? $request->input('courier_name') : null,
            'courier_service'      => $methodType === 'courier' ? $request->input('courier_service') : null,
            'courier_etd'          => $methodType === 'courier' ? $request->input('courier_etd') : null,
            'shipping_cost'        => $shippingCost,
            'shipping_zone_id'     => $zone?->id,
            'total_bayar'          => $totalBayar,
            'catatan_pengelola'    => $request->input('catatan_pengelola'),
        ]);

        // ── Generate dokumen ──────────────────────────────────────────
        $agreementPath = $this->generateAgreementDocument($penyewaan->fresh());
        $invoicePath   = $this->generateInvoiceDocument($penyewaan->fresh());

        $penyewaan->update([
            'status'                  => 'menunggu_dokumen_perjanjian',
            'payment_status'          => 'unpaid',
            'agreement_document_path' => $agreementPath,
            'invoice_document_path'   => $invoicePath,
            'signed_agreement_status' => 'pending',
        ]);

        $penyewaan->painting->update(['available' => false]);
        $penyewaan->user->notify(new PenyewaanStatusNotification($penyewaan));

        return redirect()->route('pengelola.penyewaan.show', $penyewaan)
            ->with('success', 'Pengajuan disetujui. Metode pengiriman & ongkir disimpan. Dokumen perjanjian dan invoice telah dibuat.');
    }

    public function reject(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        if ($penyewaan->status !== 'menunggu_verifikasi') {
            return redirect()->route('pengelola.penyewaan.show', $penyewaan)
                ->with('error', 'Pengajuan hanya bisa ditolak jika masih berstatus Menunggu Verifikasi.');
        }

        $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:2000'],
        ]);

        $penyewaan->update([
            'status'           => 'ditolak',
            'rejection_reason' => $request->rejection_reason,
        ]);

        $penyewaan->user->notify(new PenyewaanStatusNotification($penyewaan));

        return redirect()->route('pengelola.penyewaan.show', $penyewaan)
            ->with('success', 'Pengajuan ditolak dan penyewa telah diberi tahu.');
    }

    public function reviewSignedAgreement(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        if ($penyewaan->status !== 'verifikasi_dokumen_perjanjian') {
            return redirect()->route('pengelola.penyewaan.show', $penyewaan)
                ->with('error', 'Tidak ada dokumen perjanjian yang menunggu validasi.');
        }

        $data = $request->validate([
            'action'       => ['required', 'in:accepted,rejected'],
            'review_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($data['action'] === 'accepted') {
            $penyewaan->update([
                'signed_agreement_status'       => 'accepted',
                'signed_agreement_review_notes' => $data['review_notes'] ?? null,
                'status'                        => 'menunggu_pembayaran',
            ]);
            $penyewaan->user->notify(new PenyewaanStatusNotification($penyewaan));

            return redirect()->route('pengelola.penyewaan.show', $penyewaan)
                ->with('success', 'Dokumen perjanjian disetujui. Penyewa dapat melanjutkan ke pembayaran.');
        }

        $penyewaan->update([
            'signed_agreement_status'       => 'rejected',
            'signed_agreement_review_notes' => $data['review_notes'] ?? null,
            'status'                        => 'menunggu_dokumen_perjanjian',
        ]);
        $penyewaan->user->notify(new PenyewaanStatusNotification($penyewaan));

        return redirect()->route('pengelola.penyewaan.show', $penyewaan)
            ->with('success', 'Dokumen perjanjian ditolak. Penyewa diminta upload ulang dokumen yang diperbaiki.');
    }

    public function showReturnReview(Penyewaan $penyewaan)
    {
        $penyewaan->loadMissing('serahTerima');
        $serahTerima = $penyewaan->serahTerima;

        if (! $serahTerima) {
            return redirect()->route('pengelola.penyewaan.show', $penyewaan)
                ->with('error', 'Data serah terima tidak ditemukan.');
        }

        $reviewableStatuses = ['menunggu_konfirmasi_selesai', 'return_document_uploaded'];
        if (! in_array($serahTerima->handover_status, $reviewableStatuses, true)) {
            return redirect()->route('pengelola.penyewaan.show', $penyewaan)
                ->with('error', 'Tidak ada dokumen pengembalian yang perlu direview saat ini.');
        }

        return view('pengelola.penyewaan.return-review', compact('penyewaan', 'serahTerima'));
    }

    public function processReturnReview(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $penyewaan->loadMissing('serahTerima');
        $serahTerima = $penyewaan->serahTerima;

        if (! $serahTerima) {
            return redirect()->route('pengelola.penyewaan.show', $penyewaan)
                ->with('error', 'Data serah terima tidak ditemukan.');
        }

        $reviewableStatuses = ['menunggu_konfirmasi_selesai', 'return_document_uploaded'];
        if (! in_array($serahTerima->handover_status, $reviewableStatuses, true)) {
            return redirect()->route('pengelola.penyewaan.show', $penyewaan)
                ->with('error', 'Tidak ada dokumen pengembalian yang perlu diverifikasi.');
        }

        $data = $request->validate([
            'action'       => ['required', 'in:approved,rejected'],
            'review_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($data['action'] === 'approved') {
            $serahTerima->update([
                'handover_status'     => 'returned',
                'return_review_notes' => $data['review_notes'] ?? null,
                'return_reviewed_at'  => now(),
            ]);
            $penyewaan->update(['status' => 'selesai']);
            $penyewaan->painting->update(['available' => true]);

            $serahTerima->logs()->create([
                'status'       => 'selesai',
                'performed_by' => 'Pengelola',
                'message'      => 'Dokumen pengembalian disetujui. Penyewaan selesai.'
                    . ($data['review_notes'] ? ' Catatan: ' . $data['review_notes'] : ''),
            ]);

            $penyewaan->user->notify(new PenyewaanStatusNotification($penyewaan));

            return redirect()->route('pengelola.penyewaan.show', $penyewaan)
                ->with('success', 'Dokumen pengembalian disetujui. Penyewaan selesai dan lukisan tersedia kembali.');
        }

        $serahTerima->update([
            'handover_status'     => 'return_document_rejected',
            'return_review_notes' => $data['review_notes'] ?? null,
            'return_reviewed_at'  => now(),
        ]);
        $serahTerima->logs()->create([
            'status'       => 'return_document_rejected',
            'performed_by' => 'Pengelola',
            'message'      => 'Dokumen pengembalian ditolak.'
                . ($data['review_notes'] ? ' Alasan: ' . $data['review_notes'] : ''),
        ]);
        $penyewaan->user->notify(new PenyewaanStatusNotification($penyewaan));

        return redirect()->route('pengelola.penyewaan.show', $penyewaan)
            ->with('success', 'Dokumen ditolak. Penyewa diminta mengunggah ulang.');
    }

    public function previewReturnDocument(Penyewaan $penyewaan)
    {
        $penyewaan->loadMissing('serahTerima');
        $serahTerima = $penyewaan->serahTerima;

        if (! $serahTerima || ! $serahTerima->tenant_signed_return_document_path) {
            abort(404, 'Dokumen pengembalian tidak ditemukan.');
        }

        $path = $serahTerima->tenant_signed_return_document_path;
        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan di storage.');
        }

        return response()->file(
            Storage::disk('public')->path($path),
            ['Content-Disposition' => 'inline; filename="dokumen-pengembalian-' . $penyewaan->id . '.pdf"']
        );
    }

    public function downloadReturnDocument(Penyewaan $penyewaan)
    {
        $penyewaan->loadMissing('serahTerima');
        $serahTerima = $penyewaan->serahTerima;

        if (! $serahTerima || ! $serahTerima->tenant_signed_return_document_path) {
            abort(404, 'Dokumen pengembalian tidak ditemukan.');
        }

        $path = $serahTerima->tenant_signed_return_document_path;
        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download(
            $path,
            'Dokumen-Pengembalian-' . $penyewaan->id . '.pdf'
        );
    }

    public function requestRevision(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        if ($penyewaan->status !== 'menunggu_verifikasi') {
            return redirect()->route('pengelola.penyewaan.show', $penyewaan)
                ->with('error', 'Revisi hanya bisa diminta jika pengajuan masih berstatus Menunggu Verifikasi.');
        }

        $data = $request->validate([
            'revision_notes'     => ['required', 'string', 'max:2000'],
            'verification_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $penyewaan->update([
            'revision_notes'     => $data['revision_notes'],
            'verification_notes' => $data['verification_notes'] ?? $penyewaan->verification_notes,
        ]);

        $penyewaan->user->notify(new PenyewaanStatusNotification($penyewaan));

        return redirect()->route('pengelola.penyewaan.show', $penyewaan)
            ->with('success', 'Pengajuan diminta revisi dan penyewa telah diberi tahu.');
    }

    // ── Private helpers ───────────────────────────────────────────────────

    private function generateAgreementDocument(Penyewaan $penyewaan): string
    {
        $pdf = Pdf::loadView('documents.agreement', compact('penyewaan'))
                  ->setPaper('a4', 'portrait');

        $filename = 'agreements/' . now()->format('YmdHis') . '-' . Str::slug($penyewaan->painting->title) . '-agreement.pdf';
        Storage::disk('public')->put($filename, $pdf->output());

        return $filename;
    }

    private function generateInvoiceDocument(Penyewaan $penyewaan): string
    {
        $pdf = Pdf::loadView('documents.invoice', compact('penyewaan'))
                  ->setPaper('a4', 'portrait');

        $filename = 'invoices/' . now()->format('YmdHis') . '-' . Str::slug($penyewaan->painting->title) . '-invoice.pdf';
        Storage::disk('public')->put($filename, $pdf->output());

        return $filename;
    }

    private function syncPaymentStatus(Penyewaan $penyewaan, MidtransService $midtrans): void
    {
        if (! $penyewaan->payment_reference) {
            return;
        }

        $payment = Payment::firstWhere('order_id', $penyewaan->payment_reference);
        if (! $payment) {
            return;
        }

        try {
            $transaction = $midtrans->getTransactionStatus($payment->order_id);
        } catch (\Exception $e) {
            if ($payment->transaction_id) {
                try {
                    $transaction = $midtrans->getTransactionStatus($payment->transaction_id);
                } catch (\Exception $e2) {
                    \Log::error('Midtrans status check failed: ' . $e2->getMessage());
                    return;
                }
            } else {
                \Log::error('Midtrans status check failed: ' . $e->getMessage());
                return;
            }
        }

        $transactionStatus = $transaction->transaction_status;
        $paymentType       = $transaction->payment_type ?? null;
        $transactionId     = $transaction->transaction_id ?? null;
        $grossAmount       = $transaction->gross_amount ?? null;
        $fraudStatus       = $transaction->fraud_status ?? null;

        $paymentStatus = PaymentStatus::FAILED->value;
        if (in_array($transactionStatus, ['capture', 'settlement'], true)) {
            if ($fraudStatus === 'accept' || $fraudStatus === null) {
                $paymentStatus = PaymentStatus::PAID->value;
            }
        } elseif ($transactionStatus === 'pending') {
            $paymentStatus = PaymentStatus::PENDING->value;
        } elseif ($transactionStatus === 'expire') {
            $paymentStatus = PaymentStatus::EXPIRED->value;
        }

        $payment->update([
            'transaction_status' => $transactionStatus,
            'payment_type'       => $paymentType,
            'transaction_id'     => $transactionId,
            'gross_amount'       => $grossAmount,
            'paid_at'            => $paymentStatus === PaymentStatus::PAID->value ? now() : $payment->paid_at,
            'payload'            => array_merge($payment->payload ?? [], (array) $transaction),
        ]);

        $update = ['payment_status' => $paymentStatus];

        if ($paymentStatus === PaymentStatus::PAID->value
            && $penyewaan->status === 'menunggu_pembayaran') {
            $update['status'] = 'pengiriman';
        }

        $penyewaan->update($update);
    }
}