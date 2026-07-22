<?php

namespace App\Http\Controllers;

use App\Models\DamageInvoice;
use App\Models\DepositRefund;
use App\Models\Penyewaan;
use App\Models\SerahTerima;
use App\Models\SerahTerimaLog;
use App\Models\User;
use App\Notifications\SerahTerimaStatusNotification;
use App\Services\MidtransService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DepositController extends Controller
{
    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 24 — Pengelola: Halaman pemeriksaan akhir koleksi
    // ──────────────────────────────────────────────────────────────────

    public function showFinalInspection(Penyewaan $penyewaan)
    {
        $this->authorizePengelola();

        $penyewaan->load(['painting', 'user', 'serahTerima', 'payments', 'depositRefund', 'damageInvoice']);
        $serahTerima = $penyewaan->serahTerima;

        if (! $serahTerima || $serahTerima->handover_status !== 'returned') {
            return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
                ->with('error', 'Koleksi belum dikonfirmasi diterima. Selesaikan tahap pengembalian terlebih dahulu.');
        }

        // Hitung deposit
        $depositAmount = $penyewaan->deposit_amount ?: $penyewaan->calculateDeposit();

        return view('deposit.final_inspection', compact('penyewaan', 'serahTerima', 'depositAmount'));
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 24 — Pengelola: Simpan hasil pemeriksaan akhir
    // ──────────────────────────────────────────────────────────────────

    public function storeFinalInspection(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizePengelola();

        $serahTerima = $penyewaan->serahTerima;

        if (! $serahTerima || $serahTerima->handover_status !== 'returned') {
            return redirect()->back()->with('error', 'Status tidak sesuai untuk pemeriksaan akhir.');
        }

        // Cegah submit ulang jika sudah diperiksa
        if ($serahTerima->final_inspection_at) {
            return redirect()->back()->with('error', 'Pemeriksaan akhir sudah pernah dilakukan.');
        }

        $data = $request->validate([
            'final_checklist_frame_safe'            => ['sometimes', 'boolean'],
            'final_checklist_no_tears'              => ['sometimes', 'boolean'],
            'final_checklist_color_normal'          => ['sometimes', 'boolean'],
            'final_checklist_glass_safe'            => ['sometimes', 'boolean'],
            'final_checklist_no_mold'               => ['sometimes', 'boolean'],
            'final_checklist_packaging_safe'        => ['sometimes', 'boolean'],
            'final_checklist_matches_documentation' => ['sometimes', 'boolean'],
            'final_inspection_notes'                => ['nullable', 'string', 'max:2000'],
            'final_inspection_photo'                => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'has_damage'                            => ['required', 'boolean'],
            // Hanya wajib jika ada kerusakan
            'final_damage_type'  => ['required_if:has_damage,1', 'nullable', 'string', 'max:255'],
            'final_damage_level' => ['required_if:has_damage,1', 'nullable', 'in:ringan,sedang,berat'],
            'final_damage_cost'  => ['required_if:has_damage,1', 'nullable', 'integer', 'min:0'],
            'final_damage_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        // Upload foto pemeriksaan akhir
        $photoPath = null;
        if ($request->hasFile('final_inspection_photo')) {
            $photoPath = $request->file('final_inspection_photo')
                ->store('final_inspection/photos', 'public');
        }

        $hasDamage = (bool) $data['has_damage'];

        $serahTerima->update([
            'final_inspection_at'                   => now(),
            'final_inspection_by'                   => auth()->user()->name,
            'final_checklist_frame_safe'            => $request->boolean('final_checklist_frame_safe'),
            'final_checklist_no_tears'              => $request->boolean('final_checklist_no_tears'),
            'final_checklist_color_normal'          => $request->boolean('final_checklist_color_normal'),
            'final_checklist_glass_safe'            => $request->boolean('final_checklist_glass_safe'),
            'final_checklist_no_mold'               => $request->boolean('final_checklist_no_mold'),
            'final_checklist_packaging_safe'        => $request->boolean('final_checklist_packaging_safe'),
            'final_checklist_matches_documentation' => $request->boolean('final_checklist_matches_documentation'),
            'final_inspection_notes'                => $data['final_inspection_notes'] ?? null,
            'final_inspection_photo_path'           => $photoPath,
            'has_damage'                            => $hasDamage,
            'final_damage_type'                     => $hasDamage ? ($data['final_damage_type'] ?? null) : null,
            'final_damage_level'                    => $hasDamage ? ($data['final_damage_level'] ?? null) : null,
            'final_damage_cost'                     => $hasDamage ? ($data['final_damage_cost'] ?? 0) : 0,
            'final_damage_notes'                    => $hasDamage ? ($data['final_damage_notes'] ?? null) : null,
        ]);

        // Update status penyewaan
        $penyewaan->update(['status' => 'pemeriksaan_akhir']);

        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => 'pemeriksaan_akhir',
            'performed_by'    => auth()->user()->name,
            'message'         => 'Pengelola menyelesaikan pemeriksaan akhir koleksi. '
                . ($hasDamage
                    ? 'Ditemukan kerusakan: ' . ($data['final_damage_type'] ?? '-')
                      . '. Biaya: Rp ' . number_format($data['final_damage_cost'] ?? 0, 0, ',', '.')
                    : 'Koleksi dalam kondisi baik, tidak ada kerusakan.'),
        ]);

        $this->notifyBoth($serahTerima, 'pemeriksaan_akhir');

        return redirect()->route('pengelola.deposit.show', $penyewaan)
            ->with('success', 'Pemeriksaan akhir berhasil disimpan. Silakan proses pengembalian deposit.');
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 25 — Pengelola: Halaman pengelolaan deposit
    // ──────────────────────────────────────────────────────────────────

    public function show(Penyewaan $penyewaan)
    {
        $this->authorizePengelola();

        $penyewaan->load(['painting', 'user', 'serahTerima', 'payments', 'depositRefund', 'damageInvoice']);
        $serahTerima = $penyewaan->serahTerima;

        if (! $serahTerima || ! $serahTerima->final_inspection_at) {
            return redirect()->route('pengelola.deposit.final-inspection', $penyewaan)
                ->with('error', 'Selesaikan pemeriksaan akhir koleksi terlebih dahulu.');
        }

        $depositAmount = $penyewaan->deposit_amount ?: $penyewaan->calculateDeposit();

        return view('deposit.show', compact('penyewaan', 'serahTerima', 'depositAmount'));
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 25A — Pengelola: Kembalikan deposit penuh (tidak ada kerusakan)
    // ──────────────────────────────────────────────────────────────────

    public function storeRefund(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizePengelola();

        $serahTerima = $penyewaan->serahTerima;

        if (! $serahTerima?->final_inspection_at) {
            return redirect()->back()->with('error', 'Pemeriksaan akhir belum dilakukan.');
        }

        if ($penyewaan->depositRefund) {
            return redirect()->back()->with('error', 'Refund deposit sudah pernah diproses.');
        }

        $depositAmount = $penyewaan->deposit_amount ?: $penyewaan->calculateDeposit();

        $data = $request->validate([
            'refund_amount'   => ['required', 'integer', 'min:0', 'max:' . $depositAmount],
            'bank_name'       => ['required', 'string', 'max:100'],
            'account_number'  => ['required', 'string', 'max:50'],
            'account_holder'  => ['required', 'string', 'max:255'],
            'refund_date'     => ['required', 'date'],
            'transfer_proof'  => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'notes'           => ['nullable', 'string', 'max:1000'],
        ]);

        $proofPath = $request->file('transfer_proof')
            ->store('deposit_refunds/proofs', 'public');

        $damageDeduction = $depositAmount - $data['refund_amount'];

        $refund = DepositRefund::create([
            'penyewaan_id'     => $penyewaan->id,
            'deposit_amount'   => $depositAmount,
            'damage_deduction' => $damageDeduction,
            'refund_amount'    => $data['refund_amount'],
            'bank_name'        => $data['bank_name'],
            'account_number'   => $data['account_number'],
            'account_holder'   => $data['account_holder'],
            'refund_date'      => $data['refund_date'],
            'transfer_proof_path' => $proofPath,
            'notes'            => $data['notes'] ?? null,
            'status'           => 'processed',
            'processed_by'     => auth()->user()->name,
        ]);

        // Tentukan deposit_status berdasarkan nominal refund
        if ($data['refund_amount'] <= 0) {
            $depositStatus = 'deducted';
        } elseif ($damageDeduction > 0) {
            $depositStatus = 'partially_returned';
        } else {
            $depositStatus = 'returned';
        }

        $penyewaan->update([
            'deposit_status' => $depositStatus,
            'status'         => 'selesai',
        ]);

        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => 'deposit_' . $depositStatus,
            'performed_by'    => auth()->user()->name,
            'message'         => 'Deposit diproses. Nominal dikembalikan: Rp '
                . number_format($data['refund_amount'], 0, ',', '.')
                . ($damageDeduction > 0
                    ? '. Potongan kerusakan: Rp ' . number_format($damageDeduction, 0, ',', '.')
                    : '')
                . '. Status: ' . $depositStatus . '.',
        ]);

        $this->notifyBoth($serahTerima, 'deposit_refunded');

        return redirect()->route('pengelola.deposit.show', $penyewaan)
            ->with('success', 'Refund deposit berhasil diproses. Penyewaan dinyatakan selesai.');
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 25B — Pengelola: Buat damage invoice (biaya > deposit)
    // ──────────────────────────────────────────────────────────────────

    public function storeDamageInvoice(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizePengelola();

        $serahTerima = $penyewaan->serahTerima;

        if (! $serahTerima?->final_inspection_at || ! $serahTerima->has_damage) {
            return redirect()->back()->with('error', 'Tidak ada kerusakan yang dicatat pada pemeriksaan akhir.');
        }

        if ($penyewaan->damageInvoice) {
            return redirect()->back()->with('error', 'Invoice kerusakan sudah pernah dibuat.');
        }

        $depositAmount   = $penyewaan->deposit_amount ?: $penyewaan->calculateDeposit();
        if (!$penyewaan->deposit_amount) {
            $penyewaan->update(['deposit_amount' => $depositAmount]);
        }
        $restorationCost = $serahTerima->final_damage_cost;

        // Validasi: biaya kerusakan harus melebihi deposit untuk membuat invoice
        if ($restorationCost <= $depositAmount) {
            return redirect()->back()
                ->with('error', 'Biaya kerusakan tidak melebihi deposit. Gunakan form refund deposit sebagian.');
        }

        $additionalCharge = $restorationCost - $depositAmount;
        $invoiceNumber    = 'DMG-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
        $orderId          = 'dmg-' . $penyewaan->id . '-' . time();

        $invoice = DamageInvoice::create([
            'penyewaan_id'      => $penyewaan->id,
            'invoice_number'    => $invoiceNumber,
            'damage_type'       => $serahTerima->final_damage_type,
            'damage_level'      => $serahTerima->final_damage_level,
            'restoration_cost'  => $restorationCost,
            'deposit_amount'    => $depositAmount,
            'deposit_used'      => $depositAmount,
            'additional_charge' => $additionalCharge,
            'damage_notes'      => $serahTerima->final_damage_notes,
            'order_id'          => $orderId,
            'status'            => 'unpaid',
            'created_by'        => auth()->user()->name,
        ]);

        // Generate snap token Midtrans
        try {
            $midtrans   = new MidtransService();
            $snapToken  = $midtrans->getSnapToken([
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => $additionalCharge,
                ],
                'customer_details' => [
                    'first_name' => $penyewaan->contact_name ?? $penyewaan->nama_instansi,
                    'email'      => $penyewaan->contact_email,
                    'phone'      => $penyewaan->contact_phone,
                ],
                'item_details' => [[
                    'id'       => 'damage-' . $penyewaan->id,
                    'price'    => $additionalCharge,
                    'quantity' => 1,
                    'name'     => 'Biaya Kerusakan Koleksi: ' . $penyewaan->painting->title,
                ]],
                'callbacks' => [
                    'finish' => route('penyewaan.requests.show', $penyewaan),
                ],
            ]);
            $invoice->update(['snap_token' => $snapToken]);
        } catch (\Throwable $e) {
            Log::error('Midtrans damage invoice snap token gagal: ' . $e->getMessage());
        }

        $penyewaan->update([
            'deposit_status' => 'additional_payment_required',
            'status'         => 'menunggu_pembayaran_kerusakan',
        ]);

        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => 'damage_invoice_created',
            'performed_by'    => auth()->user()->name,
            'message'         => 'Invoice kerusakan dibuat. Nomor: ' . $invoiceNumber
                . '. Biaya restorasi: Rp ' . number_format($restorationCost, 0, ',', '.')
                . '. Deposit hangus: Rp ' . number_format($depositAmount, 0, ',', '.')
                . '. Tagihan tambahan penyewa: Rp ' . number_format($additionalCharge, 0, ',', '.') . '.',
        ]);

        $this->notifyBoth($serahTerima, 'damage_invoice_created');

        return redirect()->route('pengelola.deposit.show', $penyewaan)
            ->with('success', 'Invoice kerusakan berhasil dibuat dan dikirim ke penyewa.');
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 26 — Pengguna: Halaman detail deposit & refund
    // ──────────────────────────────────────────────────────────────────

    public function showTenant(Penyewaan $penyewaan)
    {
        $this->authorizeOwner($penyewaan);

        $penyewaan->load(['painting', 'serahTerima', 'payments', 'depositRefund', 'damageInvoice']);
        $serahTerima   = $penyewaan->serahTerima;
        $depositAmount = $penyewaan->deposit_amount ?: $penyewaan->calculateDeposit();

        return view('deposit.tenant_show', compact('penyewaan', 'serahTerima', 'depositAmount'));
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 26 — Pengguna: Halaman payment gateway damage invoice
    // ──────────────────────────────────────────────────────────────────

    public function showDamagePayment(Penyewaan $penyewaan)
    {
        $this->authorizeOwner($penyewaan);

        $invoice = $penyewaan->damageInvoice;

        if (!$invoice || $invoice->isPaid()) {
            return redirect()->back()->with('error', 'Invoice tidak ditemukan atau sudah lunas.');
        }

        // Selalu regenerate snap token setiap kali halaman dibuka (token expired ~1 jam)
        try {
            $newOrderId = 'dmg-' . $penyewaan->id . '-' . time();
            $midtrans   = new MidtransService();
            $snapToken  = $midtrans->getSnapToken([
                'transaction_details' => [
                    'order_id'     => $newOrderId,
                    'gross_amount' => $invoice->additional_charge,
                ],
                'customer_details' => [
                    'first_name' => $penyewaan->contact_name ?? $penyewaan->nama_instansi,
                    'email'      => $penyewaan->contact_email,
                    'phone'      => $penyewaan->contact_phone,
                ],
                'item_details' => [[
                    'id'       => 'damage-' . $penyewaan->id,
                    'price'    => $invoice->additional_charge,
                    'quantity' => 1,
                    'name'     => 'Biaya Kerusakan: ' . $penyewaan->painting->title,
                ]],
            ]);
            $invoice->update([
                'snap_token' => $snapToken,
                'order_id'   => $newOrderId,
            ]);
            $invoice->refresh();
        } catch (\Throwable $e) {
            Log::error('Regenerate snap token gagal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat halaman pembayaran: ' . $e->getMessage());
        }

        $clientKey = (new MidtransService())->getClientKey();

        return view('deposit.damage-payment', compact('penyewaan', 'invoice', 'clientKey'));
    }

    // ──────────────────────────────────────────────────────────────────
    //  Webhook Midtrans — Damage Invoice Payment
    // ──────────────────────────────────────────────────────────────────

    public function handleDamagePaymentWebhook(Request $request)
    {
        try {
            $midtrans = new MidtransService();
            $notification = new \Midtrans\Notification();

            $orderId           = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus       = $notification->fraud_status;
            $transactionId     = $notification->transaction_id;
            $paymentType       = $notification->payment_type;

            $invoice = DamageInvoice::where('order_id', $orderId)->first();

            if (!$invoice) {
                Log::warning('Damage invoice webhook: order_id tidak ditemukan: ' . $orderId);
                return response()->json(['message' => 'Invoice not found'], 404);
            }

            if ($transactionStatus === 'capture' && $fraudStatus === 'accept') {
                $status = 'paid';
            } elseif ($transactionStatus === 'settlement') {
                $status = 'paid';
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $status = 'failed';
            } elseif ($transactionStatus === 'pending') {
                $status = 'pending';
            } else {
                $status = $invoice->status;
            }

            $invoice->update([
                'status'             => $status,
                'transaction_id'     => $transactionId,
                'payment_type'       => $paymentType,
                'transaction_status' => $transactionStatus,
                'paid_at'            => $status === 'paid' ? now() : null,
                'payload'            => $request->all(),
            ]);

            if ($status === 'paid') {
                $penyewaan   = $invoice->penyewaan;
                $serahTerima = $penyewaan->serahTerima;

                $penyewaan->update([
                    'deposit_status' => 'deducted',
                    'status'         => 'menunggu_ttd_pengembalian',
                ]);

                if ($serahTerima) {
                    $serahTerima->update(['handover_status' => 'waiting_return_signature']);

                    SerahTerimaLog::create([
                        'serah_terima_id' => $serahTerima->id,
                        'status'          => 'damage_payment_received',
                        'performed_by'    => 'Sistem',
                        'message'         => 'Pembayaran tagihan kerusakan diterima via ' . $paymentType
                            . '. Penyewa diminta menandatangani dokumen pengembalian.',
                    ]);

                    $this->notifyBoth($serahTerima, 'damage_payment_received');
                }
            }

            return response()->json(['message' => 'OK']);

        } catch (\Throwable $e) {
            Log::error('Damage invoice webhook error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────────
    //  Private Helpers
    // ──────────────────────────────────────────────────────────────────

    protected function authorizePengelola(): void
    {
        if (auth()->user()->role !== 'pengelola') abort(403);
    }

    protected function authorizeOwner(Penyewaan $penyewaan): void
    {
        if (auth()->id() !== $penyewaan->user_id) abort(403);
    }

    protected function notifyBoth(SerahTerima $serahTerima, string $event): void
    {
        try {
            $serahTerima->penyewaan->user->notify(
                new SerahTerimaStatusNotification($serahTerima, $event)
            );
            User::where('role', 'pengelola')->get()
                ->each->notify(new SerahTerimaStatusNotification($serahTerima, $event));
        } catch (\Throwable $e) {
            Log::warning('Notifikasi deposit gagal: ' . $e->getMessage());
        }
    }
}