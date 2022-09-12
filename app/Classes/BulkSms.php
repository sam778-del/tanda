<?php

namespace App\Classes;

use App\Traits\ApiResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class BulkSms {

    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('tanda.bulksms.base_url');
    }

    public function sendSms(String $body, String $to)
    {
        $attributes = [
            'query' => [
                'api_token'=> config('tanda.bulksms.api_key'),
                'to'=> $to,
                'from'=> config('tanda.bulksms.from'),
                'body'=> $body,
                'gateway'=> '0',
                'append_sender'=> '0',
            ],
        ];
        $response = Http::withHeaders($this->header())
            ->post($this->baseUrl.'/api/v1/sms/create', $attributes);

        if($response->successful())
        {
            return $response->json();
        }
        abort(400, Arr::get($response->json(), 'message'));
    }

    public function header()
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }
}

