<?php

namespace App\Http\Controllers\Mono;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyTokenRequest;
use App\Models\User;
use App\Services\MonoService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class MonoController extends Controller
{
    public function webhook(Request $request)
    {
        logger($request->toArray());
        resolve(MonoService::class)->validateHeader($request);

        if ($request->event == 'mono.events.account_connected') {
            // create the user added on mono
            return http_response_code(200);
        }
    }

    public function verifyMonoToken(VerifyTokenRequest $request)
    {
        $mono = resolve(MonoService::class);
        $account = $mono->verifyMonoToken(['code' => $request->code]);

        if (!empty(Arr::get($account, 'id'))) {
            // Fetch account info
            $accountInfo = $mono->fetchAccountInfo(Arr::get($account, 'id'));
            if (!empty(Arr::get($accountInfo, 'account', []))) {
                $addUser = $this->addUser($request->email, $accountInfo, $mono);
                if (!empty($addUser)) {
                    return $this->createdResponse('Mono integration successful', $addUser);
                }
                return $this->badRequestAlert("Operation Failed");
            }
        }
    }

    private function addUser(string $email, array $accountInfo, MonoService $mono)
    {
        $payload = [
            'user_id' => User::where('email', $email)->first()->id,
            'mono_id' => Arr::get($accountInfo, 'account._id'),
            'auth_method' => Arr::get($accountInfo, 'meta.auth_method'),
            'name' => Arr::get($accountInfo, 'account.name'),
            'type' => Arr::get($accountInfo, 'account.type'),
            'account_number' => Arr::get($accountInfo, 'account.accountNumber'),
            'bvn' => Arr::get($accountInfo, 'account.bvn'),
            'bank_name' => Arr::get($accountInfo, 'account.institution.name'),
            'bank_code' => Arr::get($accountInfo, 'account.institution.bankCode'),
        ];
        return $mono->addMonoUser($payload);
    }
}
