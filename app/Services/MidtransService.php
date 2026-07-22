<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');

        if (empty($serverKey) || empty($clientKey)) {
            throw new \RuntimeException(
                'Midtrans configuration is incomplete. Please set MIDTRANS_SERVER_KEY and MIDTRANS_CLIENT_KEY in your .env file, then run php artisan config:clear.'
            );
        }

        Config::$serverKey = $serverKey;
        Config::$clientKey = $clientKey;
        Config::$isProduction = filter_var(config('midtrans.is_production'), FILTER_VALIDATE_BOOLEAN);
        Config::$isSanitized = filter_var(config('midtrans.is_sanitized', true), FILTER_VALIDATE_BOOLEAN);
        Config::$is3ds = filter_var(config('midtrans.is_3ds', false), FILTER_VALIDATE_BOOLEAN);
    }

    public function getClientKey(): string
    {
        return config('midtrans.client_key');
    }

    public function getSnapToken(array $params): string
    {
        return Snap::getSnapToken($params);
    }

    public function getTransactionStatus(string $orderId): object
    {
        return Transaction::status($orderId);
    }
}
