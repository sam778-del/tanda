<?php

namespace Modules\Wallet\Factories;

use Modules\Wallet\Http\Requests\AddFundRequest;

class WalletFactory
{
    public static function create(AddFundRequest $request)
    {
        if ($request->type == 'card') {
            return (new FlutterwaveFactory())->fundWallet($request->toArray());
        }

        if ($request->type == 'bank') {
            return (new OkraFactory())->fundWallet($request->toArray());
        }
    }
}
