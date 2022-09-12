<?php

namespace Modules\Wallet\Factories;

use App\Logic\Flutterwave;
use App\Traits\ApiResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FlutterwaveFactory
{
    use ApiResponse;

    public function fundWallet(array $attributes)
    {
        $payload = [
            'token' => Arr::get($attributes, 'card.token'),
            'email' => auth()->user()->email,
            'amount' => Arr::get($attributes, 'amount') * 100,
            'currency' => 'NGN',
            'country' => 'NG',
            'tx_ref' => strtoupper(Str::random(11)),
        ];

        $response = (new Flutterwave())->fundAccount($payload);
        return Arr::get($response, 'data');
    }
}
