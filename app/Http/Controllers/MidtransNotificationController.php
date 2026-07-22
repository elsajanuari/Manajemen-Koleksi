<?php

namespace App\Http\Controllers;

use App\Models\PemesananTiket;
use App\Services\MidtransPaymentService;
use Midtrans\Notification;

class MidtransNotificationController extends Controller
{
    public function handle()
    {
        MidtransPaymentService::configure();

        if (! config('midtrans.server_key')) {
            return response()->json(['message' => 'not configured'], 503);
        }

        try {
            $notif = new Notification;
            $orderId = $notif->order_id ?? null;

            if (! is_string($orderId) || $orderId === '') {
                return response()->json(['message' => 'invalid payload'], 400);
            }

            $pemesanan = PemesananTiket::query()
                ->where('midtrans_order_id', $orderId)
                ->first();

            if (! $pemesanan) {
                return response()->json(['message' => 'order not found'], 404);
            }

            MidtransPaymentService::sinkronkanDariResponsMidtrans($pemesanan, $notif->getResponse());

            return response()->json(['message' => 'ok']);
        } catch (\Throwable $e) {
            report($e);

            return response()->json(['message' => 'error'], 500);
        }
    }
}
