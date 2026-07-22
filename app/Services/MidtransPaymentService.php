<?php

namespace App\Services;

use App\Models\PemesananTiket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransPaymentService
{
    public static function configure(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = (bool) config('midtrans.is_production', false);
        Config::$isSanitized = (bool) config('midtrans.is_sanitized', true);
        Config::$is3ds = (bool) config('midtrans.is_3ds', false);
    }

    public static function ensureOrderId(PemesananTiket $pemesanan): string
    {
        if ($pemesanan->isPaid() && $pemesanan->midtrans_order_id) {
            return $pemesanan->midtrans_order_id;
        }

        $orderId = 'MUSEUM-PT-' . $pemesanan->id . '-' . strtoupper(Str::random(8));

        $pemesanan->forceFill([
            'midtrans_order_id' => $orderId
        ])->save();

        return $orderId;
    }

    public static function mapPaymentTypeToMetode(?string $paymentType): string
    {
        $paymentType = strtolower((string) $paymentType);

        $transfer = [
            'bank_transfer', 'echannel', 'permata_va', 'bca_va', 'bni_va', 'bri_va',
            'other_va', 'danamon_online', 'mandiri_va', 'cimb_va', 'bca_klikpay',
            'bca_klikbca', 'bri_epay', 'telkomsel_cash', 'retail', 'alfamart', 'indomaret',
        ];

        $ewallet = [
            'gopay', 'shopeepay', 'qris', 'other_qris', 'kioson', 'indosat_dompetku',
            'akulaku', 'uob_ezpay', 'cstore',
        ];

        if (in_array($paymentType, $transfer, true)) {
            return 'transfer_bank';
        }

        if (in_array($paymentType, $ewallet, true)) {
            return 'e_wallet';
        }

        if ($paymentType === 'credit_card') {
            return 'kartu_kredit';
        }

        return 'e_wallet';
    }

    /**
     * Terapkan status dari objek respons Midtrans (notification / status API).
     *
     * @param  object|array<int|string, mixed>  $statusResponse
     */
    public static function sinkronkanDariResponsMidtrans(PemesananTiket $pemesanan, object|array $statusResponse): bool
    {
        $statusResponse = is_array($statusResponse) ? (object) $statusResponse : $statusResponse;
        $transactionStatus = $statusResponse->transaction_status ?? null;
        $fraudStatus = $statusResponse->fraud_status ?? null;
        $paymentType = $statusResponse->payment_type ?? null;

        $paid = false;

        if ($transactionStatus === 'settlement') {
            $paid = true;
        }

        if ($transactionStatus === 'capture') {
            $paid = $fraudStatus === 'accept' || $fraudStatus === null;
        }

        if (!$paid) {
            return false;
        }

        return DB::transaction(function () use ($pemesanan, $statusResponse, $paymentType) {
            /** @var PemesananTiket $row */
            $row = PemesananTiket::query()->lockForUpdate()->findOrFail($pemesanan->id);

            if ($row->isPaid()) {
                return true;
            }

            if (!$row->isWaitingPayment()) {
                return false;
            }

            $transactionId = $statusResponse->transaction_id ?? null;

            $row->forceFill([
                'status' => 'lunas',
                'metode_pembayaran' => self::mapPaymentTypeToMetode(is_string($paymentType) ? $paymentType : null),
                'midtrans_transaction_id' => isset($transactionId) && $transactionId !== ''
                    ? (string) $transactionId
                    : null,
                'midtrans_payment_type' => is_string($paymentType) ? $paymentType : null,
                'tanggal_bayar' => now(),
                'tiket_verifikasi_token' => $row->tiket_verifikasi_token ?? Str::random(48),
            ])->save();

            $quota = $row->ticket->quotas()
                ->where('tanggal', $row->tanggal_pemesanan)
                ->first();

            if ($quota) {
                $quota->update([
                    'kuota_terjual' => $quota->kuota_terjual + $row->jumlah_tiket,
                ]);
            }

            return true;
        });
    }

    public static function createSnapToken(PemesananTiket $pemesanan): string
    {
        self::configure();

        $orderId = self::ensureOrderId($pemesanan);
        $firstDetail = $pemesanan->detailPengunjungs()->orderBy('urutan_pengunjung')->first();

        $customer = [
            'first_name' => $pemesanan->user->name ?? 'Pengunjung',
            'email' => $pemesanan->user->email ?? 'guest@example.com',
            'phone' => '081234567890',
        ];

        if ($firstDetail) {
            if ($firstDetail->isKelompok()) {
                $customer['first_name'] = $firstDetail->nama_penanggung_jawab ?? $customer['first_name'];
                $customer['email'] = $firstDetail->email_penanggung_jawab ?? $customer['email'];
                $customer['phone'] = $firstDetail->nomor_ponsel_penanggung_jawab ?? $customer['phone'];
            } else {
                $customer['first_name'] = $firstDetail->nama_lengkap ?? $customer['first_name'];
                $customer['email'] = $firstDetail->email ?? $customer['email'];
                $customer['phone'] = $firstDetail->nomor_ponsel ?? $customer['phone'];
            }
        }

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $pemesanan->total_harga,
            ],
            'customer_details' => $customer,
            'item_details' => [
                [
                    'id' => (string) $pemesanan->ticket_id,
                    'price' => (int) $pemesanan->ticket->harga,
                    'quantity' => (int) $pemesanan->jumlah_tiket,
                    'name' => substr($pemesanan->ticket->nama_tiket, 0, 50),
                ],
            ],
            'callbacks' => [
                'finish' => route('pemesanan-tiket.show', $pemesanan, absolute: true),
            ],
        ];

        return Snap::getSnapToken($params);
    }
}
