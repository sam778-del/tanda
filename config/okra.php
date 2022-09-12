<?php

return [
    'secret' => env('OKRA_SECRET'),
    'api_key' => env('OKRA_API_KEY'),
    'base_url' => env('OKRA_MODE') == 'sandbox' ? env('OKRA_SANDBOX_URL') : env('OKRA_PRODUCTION_URL'),
];
