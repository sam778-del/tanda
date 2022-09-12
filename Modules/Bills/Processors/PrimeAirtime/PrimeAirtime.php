<?php


namespace Modules\Bills\Processors\PrimeAirtime;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Modules\Bills\Models\Bill;

class PrimeAirtime
{

    private string $baseUrl;
    private $token;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->baseUrl = config('tanda.prime_airtime.' . config('tanda.prime_airtime.mode') . '.base_url');
        $this->username = config('tanda.prime_airtime.' . config('tanda.prime_airtime.mode') . '.username');
        $this->password = config('tanda.prime_airtime.' . config('tanda.prime_airtime.mode') . '.password');
        $this->token = Arr::get($this->generateToken(), 'token');
    }

    public function generateToken()
    {
        return Cache::remember('prime_token', now()->addHours(24), function () {
            return Http::post($this->baseUrl.'/api/auth', [
                'username' => $this->username,
                'password' => $this->password,
            ])->json();
        });
    }

    public function getPrimeProducts($service)
    {
        return Http::withHeaders($this->headers())
            ->get($this->baseUrl."/api/billpay/country/NG/{$service}")
            ->throw()
            ->json();
    }

    public function verifyPhoneNumber(Bill $bill, string $msisdn)
    {
        if ($bill->category == Bill::AIRTIME || $bill->category == Bill::MOBILE_DATA) {
            return $this->networkValidation($bill->category, $msisdn);
        }
        throw new \Exception("Invalid bill service selected");
    }

    public function networkValidation(string $service, string $msisdn)
    {
        $uri = $service == Bill::AIRTIME ? '/api/topup/info/'.$msisdn : '/api/datatopup/info/'.$msisdn;
        return Http::withHeaders($this->headers())
            ->get($this->baseUrl.$uri)
            ->json();
    }

    public function purchase(Bill $bill, string $msisdn, float $amount, string $transactionRef)
    {
        $payload = [
            'customer_reference' => $transactionRef,
            'product_id' => $bill->service_code,
            'send_sms' => false,
            'denomination' => $bill->category == Bill::AIRTIME ? (int) $amount : $bill->amount,
            'sms_text' => ''
        ];
        if ($bill->category == Bill::AIRTIME || $bill->category == Bill::MOBILE_DATA) {
            return $this->purchaseAirtimeData($bill->category, $msisdn, $payload);
        }
        throw new \Exception("Invalid bill service selected");
    }

    public function reQuery()
    {

    }

    private function purchaseAirtimeData(string $service, string $msisdn, array $payload)
    {
        $uri = $service == Bill::AIRTIME ? '/api/topup/exec/'.$msisdn : '/api/datatopup/exec/' .$msisdn;
        return Http::withHeaders($this->headers())
            ->post($this->baseUrl.$uri, $payload)
            ->json();
    }

    public function validateInternetCableAndElectricity(string $service_id, string $product_id, array $payload)
    {
        return Http::withHeaders($this->headers())
            ->post($this->baseUrl."/api/billpay/{$service_id}/{$product_id}/validate", $payload)
            ->json();
    }

    public function validateDstvGotv(string $iucNumber)
    {
        return Http::withHeaders($this->headers())
            ->get($this->baseUrl.'/api/billpay/dstvnew/'.$iucNumber)
            ->json();
    }

    public function reQueryTransaction($customerReference)
    {
        return Http::withHeaders($this->headers())
            ->get($this->baseUrl.'/api/topup/log/byref/'.$customerReference)
            ->json();
    }

    public function validatePhoneNumber($phone)
    {
        return Http::withHeaders($this->headers())
            ->get($this->baseUrl.'/api/nv/active/'.$phone)
            ->json();
    }


    private function headers(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->token
        ];
    }
}
