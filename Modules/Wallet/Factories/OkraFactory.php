<?php

namespace Modules\Wallet\Factories;

use App\Services\OkraService;
use Illuminate\Support\Arr;

class OkraFactory
{
    public function fundWallet(array $attributes)
    {
        $payload = [
            'account_to_debit' => auth()->user()->okraCredentials->okra_customer_id,
            'account_to_credit' => config("tanda.okra.account_id"),
            'amount' => Arr::get($attributes, 'amount') * 100,
            'currency' => 'NGN',
        ];
        $response = (new OkraService())->debit($payload);
        return Arr::get($response, 'data');

    }
}
