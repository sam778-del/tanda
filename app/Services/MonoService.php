<?php

namespace App\Services;

use App\Models\MonoUser;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class MonoService
{
    use ApiResponse;

    private $publicKey;
    private $baseUrl;
    private $secretKey;

    public function __construct()
    {
        $this->publicKey = config("tanda.mono.api_key");
        $this->secretKey = config("tanda.mono.secret");
        $this->baseUrl = config('tanda.mono.base_url');
    }
//Mono Changes
    public function GetPhysicalCard(string $id)
    {
        $response =  Http::withHeaders($this->header())
            ->get($this->baseUrl.'/issuing/v1/cards/'.$id);

        if ($response->successful()) {
            return $response->json();
        }

        abort(400, Arr::get($response->json(), 'message'));
    }
//Mono Changes
    public function CreatePhysicalCard(array $attributes)
    {
        $response =  Http::withHeaders($this->header())
            ->post($this->baseUrl.'/issuing/v1/cards/physical', $attributes);

        if ($response->successful()) {
            return $response->json();
        }

        abort(400, Arr::get($response->json(), 'message'));
    }

//Mono Changes
    public function Fund(array $attributes)
    {
        $response =  Http::withHeaders($this->header())
            ->post($this->baseUrl.'/issuing/v1/wallets/fund',$attributes);

        if ($response->successful()) {
            return $response->json();
        }


        abort(400, Arr::get($response->json(), 'message'));
    }
//Mono Changes
    public function GetVirtualCard(string $id)
    {
        $response =  Http::withHeaders($this->header())
            ->get($this->baseUrl.'/issuing/v1/cards/'.$id);

        if ($response->successful()) {
            return $response->json();
        }


        abort(400, Arr::get($response->json(), 'message'));
    }
//Mono Changes
    public function CreateVirtualCard(array $attributes)
    {
        $response =  Http::withHeaders($this->header())
            ->post($this->baseUrl.'/issuing/v1/cards/virtual', $attributes);

        if ($response->successful()) {
            return $response->json();
        }


        abort(400, Arr::get($response->json(), 'message'));
    }

//Mono Changes
    public function GetVirtualAccount(string $id)
    {
        $response =  Http::withHeaders($this->header())
            ->get($this->baseUrl.'/issuing/v1/virtualaccounts/'.$id);

        if ($response->successful()) {
            return $response->json();
        }

        abort(400, Arr::get($response->json(), 'message'));
    }
//Mono Changes
    public function CreateVirtualAccount(array $attributes)
    {
        $response =  Http::withHeaders($this->header())
            ->post($this->baseUrl.'/issuing/v1/virtualaccounts', $attributes);

        if ($response->successful()) {
            return $response->json();
        }

        abort(400, Arr::get($response->json(), 'message'));
    }

//Mono Changes
    public function CreateAccountHolder(array $attributes)
    {
        $response =  Http::withHeaders($this->header())
            ->post($this->baseUrl.'/issuing/v1/accountholders', $attributes);
        if ($response->successful()) {
            return $response->json();
        }

        abort(400, Arr::get($response->json(), 'message'));
    }

//Mono Changes
    public function GetAccountHolder()
    {
        $response =  Http::withHeaders($this->header())
            ->get($this->baseUrl.'/issuing/v1/accountholders');

        if ($response->successful()) {
            return $response->json();
        }

        abort(400, Arr::get($response->json(), 'message'));
    }

//Mono Changes
    public function verifyMonoToken(array $attributes)
    {
        $response =  Http::withHeaders($this->header())
            ->post($this->baseUrl.'/account/auth', $attributes);

        if ($response->successful()) {
            return $response->json();
        }

        abort(400, Arr::get($response->json(), 'message'));
    }

    public function fetchAccountInfo(string $id)
    {
        $response =  Http::withHeaders($this->header())
            ->post($this->baseUrl.'/accounts'.$id);

        if ($response->successful()) {
            return $response->json();
        }

        abort(400, Arr::get($response->json(), 'message'));
    }

    public function validateHeader($request)
    {
        abort_unless($request->header('mono-webhook-secret') == $this->secretKey, 400, 'Invalid secret key credentials');
    }

    public function addMonoUser(array $attribute)
    {
        // create a mono user on the database
        return MonoUser::create($attribute);
    }

    public function header()
    {
        return [
            'Content-Type' => 'application/json',
            'mono-sec-key' => config("tanda.mono.secret_key")
        ];
    }
}
