<?php

namespace App\Services;

use App\Models\PemesananTiket;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Midtrans\Config;

class MidtransRefundService
{
    public static function refundPemesanan(PemesananTiket $pemesanan): array
    {
        MidtransPaymentService::configure();

        $serverKey = config('midtrans.server_key');
        if (! $serverKey || ! $pemesanan->midtrans_order_id) {
            throw new \RuntimeException('Refund tidak dapat diproses: data pembayaran Midtrans tidak lengkap.');
        }

        $refundKey = $pemesanan->midtrans_refund_key
            ?? ('refund-pt-' . $pemesanan->id . '-' . strtoupper(Str::random(8)));

        $baseUrl = Config::$isProduction
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';

        $response = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->post($baseUrl . '/v2/' . $pemesanan->midtrans_order_id . '/refund', [
                'refund_key' => $refundKey,
                'amount' => (int) $pemesanan->total_harga,
                'reason' => 'Pembatalan pemesanan tiket oleh pengguna',
            ]);

        if (! $response->successful()) {
            $message = $response->json('status_message')
                ?? $response->json('error_messages.0')
                ?? 'Gagal memproses refund ke Midtrans.';

            throw new \RuntimeException($message);
        }

        return [
            'refund_key' => $refundKey,
            'response' => $response->json(),
        ];
    }
}
