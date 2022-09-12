<?php


namespace App\Services;


use App\Models\OkraUser;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class OkraService
{
    use ApiResponse;

    public $apiKey;
    private $baseUrl;
    public $secret;

    public function __construct()
    {
        $this->apiKey = config("tanda.okra.api_key");
        $this->secret = config("tanda.okra.secret");
        $this->baseUrl = config('tanda.okra.base_url');
    }

    public function customerAuthenticate(array $requestData)
    {
        $user = User::find($requestData['options'][0]);
        if (empty($user)) {
            abort(404, 'user not found');
        }
        $okraCredentials = OkraUser::create([
            'user_id' => $user->id,
            'okra_customer_id' => Arr::get($requestData, 'customerId'),
            'okra_record_id' => Arr::get($requestData, 'recordId'),
            'okra_bank_id' => Arr::get($requestData, 'bankId'),
            'okra_bank_type' => Arr::get($requestData, 'bankType'),
            'okra_bank_name' => Arr::get($requestData, 'bankName'),
        ]);

        $user->is_okra_verified = true;
        $user->save();

        return $this->ok($okraCredentials);
    }

    public function getBanks()
    {
        $response = Http::get($this->baseUrl . '/banks/list')->json();
        if ($response['status'] == 'success') {
            return $this->ok($response['data']['banks']);
        }
        abort(400, 'Operation failed');
    }

    public function generatePaymentLink(array $payload)
    {
        $payload = array_merge($payload, [
            'currency' => 'NGN',
            'color' => '#ff0038',
            'data' => false,
        ]);

        Http::withHeaders($this->headers())
            ->post($this->baseUrl . '/pay/link/create', $payload)
            ->json();
    }

    public function debit(array $payload)
    {
        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl . '/pay/initiate', $payload)
            ->json();

        if (Arr::get($response, 'status') == 'success') {
            return $this->ok(Arr::get($response, 'data'));
        }
        abort(400, Arr::get($response, 'message'));
    }

    public function futureDebit(array $attribute)
    {
        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl . '/pay/authorization/initiate', $attribute)
            ->json();
        if (Arr::get($response, 'status') == 'success') {
            return $this->ok(Arr::get($response, 'data'));
        }
        abort(400, Arr::get($response, 'message'));
    }

    public function payout(array $payload)
    {
        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl . '/pay/payout', $payload)
            ->json();

        if (Arr::get($response, 'status') == 'success') {
            return $this->ok(Arr::get($response, 'data'));
        }
        abort(400, Arr::get($response, 'message'));
    }

    public function refund(array $payload)
    {
        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl . '/pay/refund', $payload)
            ->json();

        if (Arr::get($response, 'status') == 'success') {
            return $this->ok(Arr::get($response, 'data'));
        }
        abort(400, Arr::get($response, 'message'));
    }

    private function headers(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->secret
        ];
    }

}
