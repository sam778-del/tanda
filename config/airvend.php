<?php
$base_url = env('AIRVEND_URL', 'https://api.airvendng.net/');

return [
    "api_key" => env("AIRVEND_API_KEY", "AXUNEOk4ks24352$%#w2323"),
    'services' => [
        "verification" => $base_url . "secured/seamless/verify/",
        "purchase" => $base_url . "secured/seamless/vend/",
        "get_billers" => $base_url . "secured/seamless/services/",
        "get_products" => $base_url . "secured/seamless/products/",
        "get_balance" => $base_url . "secured/seamless/balance/",

    ],
    "types" => [
        1 => "airtime",
        2 => "databundle",
        3 => "epin",
        90 => "epin",
        80 => "epin",
        81 => "epin",
        4 => "electricity",
        30 => "cabletv",
        40 => "cabletv",
        70 => "cabletv",
        50 => "airtime", //smile top up
        60 => "databundle", //smile bundle
    ],

    "credentials" => [
        "test" => [
            "url" => env("AIRVEND_URL_TEST", "http://test.airvendng.net/"),
            "username" => env("AIRVEND_USERNAME_TEST", "admin"),
            "password" => env("AIRVEND_PASSWORD_TEST", "admin"),
            "api_key" => env("AIRVEND_API_KEY", "AXUNEOk4ks24352$%#w2323"),
        ],

        "production" => [
            "url" => env("AIRVEND_URL", "https://api.airvendng.net/"),
            "username" => env("AIRVEND_USERNAME", "smscafe202@gmail.co"),
            "password" => env("AIRVEND_PASSWORD", "euzzit3334$"),
            "api_key" => env("AIRVEND_API_KEY", "AXUNEOk4ks24352$%#w2323"),
        ]
    ]

];
