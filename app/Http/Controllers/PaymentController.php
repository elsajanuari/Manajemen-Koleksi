<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Penyewaan;
use App\Models\SerahTerima;
use App\Models\SerahTerimaLog;
use App\Notifications\PenyewaanStatusNotification;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\DamageInvoice;
 
class PaymentController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('Midtrans Callback Received', $request->all());
 
        // Validasi signature key
        $orderId           = $request->order_id;
        $statusCode        = $request->status_code;
        $grossAmount       = $request->gross_amount;
        $serverKey         = config('midtrans.server_key');
        $signatureKey      = $request->signature_key;
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
 
        if ($signatureKey !== $expectedSignature) {
            Log::warning('Midtrans callback: invalid signature', ['order_id' => $orderId]);
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
        }
 
        $transactionStatus = $request->transaction_status;
        $paymentType       = $request->payment_type;
        $fraudStatus       = $request->fraud_status ?? null;
 
        // ─── Cek apakah ini callback untuk damage invoice ───────────
        if (str_starts_with($orderId, 'dmg-')) {
            return $this->handleDamageCallback($request, $orderId, $transactionStatus, $paymentType, $fraudStatus);
        }
 
        // ─── Callback pembayaran sewa normal ────────────────────────
        $payment = Payment::where('order_id', $orderId)->first();
 
        if (! $payment) {
            Log::error('Payment not found for order_id: ' . $orderId);
            return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
        }
 
        $penyewaan = $payment->penyewaan;
 
        if (in_array($transactionStatus, ['settlement', 'capture'], true)) {
            if ($fraudStatus !== null && $fraudStatus !== 'accept') {
                $payment->update(['transaction_status' => $transactionStatus, 'payload' => $request->all()]);
                $penyewaan->update(['payment_status' => 'failed']);
                return response()->json(['status' => 'ok']);
            }
 
            $payment->update([
                'transaction_status' => $transactionStatus,
                'payment_type'       => $paymentType,
                'gross_amount'       => $grossAmount,
                'paid_at'            => now(),
                'payload'            => $request->all(),
            ]);
 
            $penyewaan->update([
                'payment_status'  => 'paid',
                'status'          => 'pengiriman',
                'deposit_amount'  => $penyewaan->calculateDeposit(),
                'deposit_status'  => 'paid',
            ]);
 
            $penyewaan->refresh();
            $this->generateHandoverDocument($penyewaan);
            $penyewaan->user->notify(new PenyewaanStatusNotification($penyewaan));
            Log::info('Payment successful for order_id: ' . $orderId);
 
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'], true)) {
            $payment->update(['transaction_status' => $transactionStatus, 'payload' => $request->all()]);
            $penyewaan->update(['payment_status' => 'failed']);
 
        } elseif ($transactionStatus === 'pending') {
            $payment->update(['transaction_status' => $transactionStatus, 'payload' => $request->all()]);
        }
 
        return response()->json(['status' => 'ok']);
    }
 
    // ─── Handle callback khusus damage invoice ──────────────────────
 
    private function handleDamageCallback(
        Request $request,
        string $orderId,
        string $transactionStatus,
        ?string $paymentType,
        ?string $fraudStatus
    ) {
        $invoice = DamageInvoice::where('order_id', $orderId)->first();
 
        if (! $invoice) {
            Log::error('DamageInvoice not found for order_id: ' . $orderId);
            return response()->json(['status' => 'error', 'message' => 'Invoice not found'], 404);
        }
 
        $penyewaan   = $invoice->penyewaan;
        $serahTerima = $penyewaan->serahTerima;
 
        if (in_array($transactionStatus, ['settlement', 'capture'], true)) {
            if ($fraudStatus !== null && $fraudStatus !== 'accept') {
                $invoice->update(['transaction_status' => $transactionStatus, 'status' => 'failed', 'payload' => $request->all()]);
                return response()->json(['status' => 'ok']);
            }
 
            $invoice->update([
                'transaction_id'     => $request->transaction_id,
                'transaction_status' => $transactionStatus,
                'payment_type'       => $paymentType,
                'paid_at'            => now(),
                'payload'            => $request->all(),
                'status'             => 'paid',
            ]);
 
            // Deposit hangus, tagihan tambahan lunas → penyewaan selesai
            $penyewaan->update([
                'deposit_status' => 'deducted',
                'status'         => 'selesai',
            ]);
 
            if ($serahTerima) {
                SerahTerimaLog::create([
                    'serah_terima_id' => $serahTerima->id,
                    'status'          => 'damage_paid',
                    'performed_by'    => 'Sistem (Midtrans)',
                    'message'         => 'Penyewa berhasil membayar tagihan kerusakan. '
                        . 'Nominal: Rp ' . number_format($invoice->additional_charge, 0, ',', '.')
                        . '. Penyewaan dinyatakan selesai.',
                ]);
            }
 
            Log::info('Damage invoice paid for order_id: ' . $orderId);
 
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'], true)) {
            $invoice->update([
                'transaction_status' => $transactionStatus,
                'status'             => $transactionStatus === 'expire' ? 'expired' : 'failed',
                'payload'            => $request->all(),
            ]);
 
        } elseif ($transactionStatus === 'pending') {
            $invoice->update([
                'transaction_status' => $transactionStatus,
                'status'             => 'pending',
                'payload'            => $request->all(),
            ]);
        }
 
        return response()->json(['status' => 'ok']);
    }
 
    // ─── Generate dokumen serah terima awal ─────────────────────────
 
    private function generateHandoverDocument(Penyewaan $penyewaan): void
    {
        if (! in_array($penyewaan->status, ['pengiriman', 'aktif'], true)) return;
        if ($penyewaan->serahTerima) return;
 
        $serahTerima = SerahTerima::firstOrCreate(
            ['penyewaan_id' => $penyewaan->id],
            [
                'document_number'        => 'HT-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                'handover_status'        => 'waiting_handover',
                'handover_document_path' => '',
            ]
        );
 
        $documentService = new DocumentService();
        $filePath = $documentService->generateHandoverDocument($penyewaan, $serahTerima);
        $serahTerima->update(['handover_document_path' => $filePath]);
 
        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => 'waiting_handover',
            'performed_by'    => 'Sistem',
            'message'         => 'Dokumen serah terima awal dibuat otomatis setelah pembayaran berhasil.',
        ]);
    }
}