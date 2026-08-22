<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    // Client requirement (Aug 2026): only FPX is used in suksel-3.0. eBPG code stays
    // in place (do not remove) but is switched off everywhere via this single flag —
    // set EBPG_ENABLED=true to bring it back without touching any controller/view.
    'ebpg' => [
        'enabled' => env('EBPG_ENABLED', false),
    ],

    'stos_backend' => [
        'url' => env('STOS_BACKEND_URL', 'https://stos-epenilaian-web.test'),
        'api_key' => env('STOS_BACKEND_API_KEY'),
        'inbound_api_key' => env('STOS_INBOUND_API_KEY', env('STOS_BACKEND_API_KEY')),
        'verify_ssl' => env('STOS_BACKEND_VERIFY_SSL', env('APP_ENV') === 'production'),
        // Optional absolute path to STOS storage/app/public (used when /storage URLs are blocked)
        'storage_path' => env('STOS_BACKEND_STORAGE_PATH'),
    ],
];
