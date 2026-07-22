<?php

return [

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // ── Binderbyte (ongkir + wilayah + tracking) ─────────────────────
    'binderbyte' => [
        'api_key'     => env('BINDERBYTE_API_KEY'),
        'origin_city' => env('BINDERBYTE_ORIGIN_CITY', 'purwakarta'),
    ],

    'rajaongkir' => [
        'api_key'        => env('RAJAONGKIR_API_KEY'),
        'base_url'       => env('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter'),
        'origin_city_id' => env('RAJAONGKIR_ORIGIN_CITY_ID', 429),
    ],

];