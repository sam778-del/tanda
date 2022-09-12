<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Okra
{
    protected $baseUrl;
    protected $api_key;
    protected $secret;

    public function __construct()
    {
        $this->baseUrl = config('okra.base_url');
        $this->api_key = config('okra.api_key');
        $this->secret = config('okra.secret');
    }

    public function getBanks()
    {
        return Http::get("https://okra.ng/v2/banks/list")->json();
    }

}
