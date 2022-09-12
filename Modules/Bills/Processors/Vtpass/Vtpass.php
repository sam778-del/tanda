<?php

namespace Modules\Bills\Processors\Vtpass;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Bills\Models\Bill;

class Vtpass
{
    private string $baseUrl;
    private string $username;
    private string $password;


    public function __construct()
    {
        $this->baseUrl = config('tanda.vtpass.' . config('tanda.vtpass.mode') . '.base_url');
        $this->username = config('tanda.vtpass.' . config('tanda.vtpass.mode') . '.username');
        $this->password = config('tanda.vtpass.' . config('tanda.vtpass.mode') . '.password');
    }

    public function fetchService(string $identifier)
    {
        $response =  Http::withBasicAuth($this->username, $this->password)->get($this->baseUrl . '/services', [
            'identifier' => $identifier
        ])
            ->json();

        if (!empty(Arr::get($response, 'content', []))) {
            return Arr::get($response, 'content', []);
        }

        Log::info(print_r($response, true));
        abort(400, "Identifier not found");
    }

    public function fetchServiceVariation(string $service)
    {
        return Http::withBasicAuth($this->username, $this->password)->get($this->baseUrl . '/service-variations', [
            'serviceID' => $service
        ])
            ->json();
    }

    public function verifyElectricityAndCableTv(Bill $bill, string $msisdn)
    {
        if ($bill->category == Bill::TV_SUBSCRIPTION || $bill->category == Bill::ELECTRICITY) {
            $payload = [
                'billersCode' => $msisdn,
                'serviceID' => strtolower($bill->group_name)
            ];
            if (Bill::ELECTRICITY == $bill->category) {
                $payload['type'] = $bill->service_code;
            }
            return Http::withBasicAuth($this->username, $this->password)
                ->post($this->baseUrl . '/merchant-verify', $payload)
                ->json();
        }
    }

    public function purchase(Bill $bill, string $msisdn, float $amount, string $transactionRef)
    {
        $payload = [
            'request_id' => $transactionRef,
            'serviceID' => strtolower($bill->group_name),
            'billersCode' => $msisdn,
            "phone" => $msisdn,
            'variation_code' => $bill->service_code,
            'amount' => $bill->category == Bill::ELECTRICITY ? $amount : $bill->amount,
        ];
        if ($bill->category == Bill::TV_SUBSCRIPTION) {
            $payload['subscription_type'] = 'change';
        }

        return Http::withBasicAuth($this->username, $this->password)
            ->post($this->baseUrl.'/pay', $payload)
            ->json();
    }

    public function reQuery()
    {

    }
}
