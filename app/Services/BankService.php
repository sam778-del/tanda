<?php 
namespace App\Services;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class BankService
{
    use ApiResponse;

    private $baseUrl;
    private $publicKey;
    private $privateKey;

    //wallent base
    private $walletBaseUrl;
    private $walletPassword;
    private $walletClientId;
    private $walletClientSecret;

    public function __construct()
    {
        $this->baseUrl = config('tanda.9bsp.base_url');
        $this->publicKey = config('tanda.9bsp.public_key');
        $this->privateKey = config('tanda.9bsp.private_key');

        //wallet
        $this->walletBaseUrl = config('tanda.wallet.base_url');
        $this->walletPassword = config('tanda.wallet.pasword');
        $this->walletClientId = config('tanda.wallet.clientid');
        $this->walletClientSecret = config('tanda.wallet.clientsecret');
        
    }

    public function CreateVirtualAccount(array $attributes)
    {
        $response = Http::withHeaders($this->header())
            ->post($this->baseUrl.'/vmw-api/v1/merchant/account/generate', $attributes);
        if($response->successful())
        {
            return $response->json();
        }
        abort(400, Arr::get($response->json(), 'message'));
    }

    public function GetVirtualAccount(array $attributes)
    {
        $response = Http::withHeaders($this->header())
            ->post($this->baseUrl.'/ipaymw-api/v1/merchant/account/enquiry', $attributes);
        if($response->successful())
        {
            return $response->json();
        }
        abort(400, Arr::get($response->json(), 'message'));
    }

    public function generateBearerToken()
    {
        $response = Http::withHeaders($this->passBearerHeader())
            ->post($this->baseUrl.'/vmw-api/v1/merchant/authenticate', $this->rawData());
        if($response->successful())
        {
            return $response->json();
        }
        abort(400, Arr::get($response->json(), 'message'));
    }

    public function passBearerHeader()
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    public function rawData()
    {
        return [
            'publickey' => $this->publicKey,
            'privatekey' => $this->privateKey
        ];
    }

    public function header()
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->generateBearerToken()["access_token"] . ' '
        ];
    }
}