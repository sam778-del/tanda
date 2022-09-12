<?php
return [
    "app_name" => "Tanda",

    "sms"=> [
        "sender" => env("SMS_SENDER", "Termii"),
        "termii" => [
            "company" => env("TERMII_COMPANY", "Tanda"),
            "api_key" => env("TERMII_API_KEY"),
            "secret" => env("TERMII_SECRET"),
            "base_url" => env("TERMII_BASE_URL", "https://termii.com/api/")
        ]
    ],

    "bulksms" => [
        "base_url" => env("BULK_BASE_URL", "https://www.bulksmsnigeria.com"),
        "from" => env("SMS_SENDER", "Tanda"),
        "api_key" => env("BULK_SMS_KEY", "B73H7PD6EBVHxCuzDoiRaoL4bO8sU4Ud1Rxsqfj9opUtER8aXp9ZVsCevk6K")
    ],

    "admin_emails" => [
        "admin@tanda.com"
    ],

    "bvn" => [
        "base_url" => env("BVN_BASE_URL", "")
    ],

    "okra" => [
        "link" => env("OKRA_WIDGET_LINK", "https://app.okra.ng/ocrkFCXgc"),
        'secret' => env('OKRA_SECRET'),
        'api_key' => env('OKRA_API_KEY'),
        'token' => env('OKRA_TOKEN'),
        'base_url' => env('OKRA_MODE') == 'sandbox' ? env('OKRA_SANDBOX_URL') : env('OKRA_PRODUCTION_URL'),
        'account_id' => env('OKRA_TANDA_ACCOUNT_ID'),
    ],

    'mono' => [
        "base_url" => env("MONO_BASE_URL", "https://api.withmono.com"),
        'public_secret' => env('MONO_PUBLIC_KEY'),
        'secret_key' => env('MONO_SECRET_KEY'),
    ],

    '9bsp' => [
        "base_url" => env("9PSB_BASE_URL", "https://baasstaging.9psbapi.com"),
        'public_key' => env('9PSB_PUBLIC_KEY', 'pubs_ZfQXmVBVnH9NNMxwXUPrdwhkCU72gMbeBNt'),
        'private_key' => env('9PSB_PRIVATE_KEY', 'prvs_eZsgJ6eL2JYj8YUMkAyLnrMYF9RQHbH65bp'),
    ],

    'wallet' => [
        'base_url' => env("WALLET_URL", ""),
        "password" => env("WALLET_PASSWORD", ""),
        "clientid" => env("WALLET_CLIENT_ID", ""),
        "clientsecret" => env("WALLET_CLIENT_SECRET", ""),
    ],


    'flutterwave' => [
        'base_url' => env('FLUTTERWAVE_BASE_URL', 'https://api.flutterwave.com/v3'),
        'mode' => env('FLUTTERWAVE_MODE', 'sandbox'),
        'sandbox' => [
            'secret_key'    => env('FLUTTERWAVE_SANDBOX_SECRET_KEY', 'sk_test_d641ea7238fc3f4b59eb61238fab11cc7b527328'),
            'public_key'    => env('FLUTTERWAVE_SANDBOX_PUBLIC_KEY', 'pk_test_ba766c0e027809c9effbb3591ede0ea25620a914'),
            'encryption_key'    => env('FLUTTERWAVE_SANDBOX_ENCRYPTION_KEY', 'FLWSECK_TEST6da92f1dd08c'),
        ],
        'production' => [
            'secret_key'    => env('FLUTTERWAVE_PRODUCTION_SECRET_KEY', 'sk_test_d641ea7238fc3f4b59eb61238fab11cc7b527328'),
            'public_key'    => env('FLUTTERWAVE_PRODUCTION_PUBLIC_KEY', 'pk_test_ba766c0e027809c9effbb3591ede0ea25620a914'),
            'encryption_key'    => env('FLUTTERWAVE_SANDBOX_ENCRYPTION_KEY', 'FLWSECK_TEST6da92f1dd08c'),
        ]
    ],
    'prime_airtime' => [
        'mode' => 'production',
        'sandbox' => [
            'base_url' => 'https://clients.sandbox.primeairtime.com',
            'username' => env('PRIME_AIRTIME_USERNAME'),
            'password' => env('PRIME_AIRTIME_PASSWORD'),
        ],
        'production' => [
            'base_url' => 'https://clients.primeairtime.com',
            'username' => env('PRIME_AIRTIME_USERNAME'),
            'password' => env('PRIME_AIRTIME_PASSWORD'),
        ]
    ],
    'vtpass' => [
        'mode' => 'sandbox',
        'sandbox' => [
            'base_url' => 'https://sandbox.vtpass.com/api',
            'username' => env('VTPASS_USERNAME'),
            'password' => env('VTPASS_PASSWORD'),
        ],
        'production' => [
            'base_url' => 'https://vtpass.com/api',
            'username' => env('VTPASS_USERNAME'),
            'password' => env('VTPASS_PASSWORD'),
        ]
    ],
];
