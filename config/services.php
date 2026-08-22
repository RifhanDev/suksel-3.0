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

    'fpx' => [
        // Staged rollout of PayNet response-signature verification.
        //   false = log-only: verification runs and is logged, but a failure does NOT
        //           block the transaction. Use this first, on real traffic, to confirm
        //           the checksum field set matches before enforcing.
        //   true  = enforce: a failed signature rejects the callback.
        // Flip to true only after the logs show verification passing on a real payment.
        'verify_signature' => env('FPX_VERIFY_SIGNATURE', false),

        // TLS verification for our outbound calls to PayNet. Should stay true; exists
        // only so an environment behind a TLS-intercepting proxy can opt out explicitly
        // instead of the old behaviour of hardcoding 'verify' => false in every call.
        'verify_tls' => env('FPX_VERIFY_TLS', true),

        // Bank-list endpoint. Separate from the gateway's endpoint_url/daemon_url
        // columns, which point at the AR (browser redirect) and AE (status enquiry)
        // paths respectively. Default matches the URL previously hardcoded in
        // Fpx::bankList(), so behaviour is unchanged unless explicitly overridden.
        'bank_list_url' => env('FPX_BANK_LIST_URL', 'https://www.mepsfpx.com.my/FPXMain/RetrieveBankList'),
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
