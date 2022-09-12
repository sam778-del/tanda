<?php


namespace App\Logic;

use App\Traits\ApiResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class Flutterwave
{
    use ApiResponse;

    /**
     * @param $data
     * @return string
     */
    public function encryptData(string $data): string
    {
        $encData = openssl_encrypt($data, 'DES-EDE3', 'FLWSECK_TEST6da92f1dd08c', OPENSSL_RAW_DATA);
        return base64_encode($encData);
    }

    /**
     * @param array $attributes
     * @return array|\Illuminate\Support\Fluent|object
     */
    public function initiatePayment(array $attributes)
    {
        $data = $this->encryptData(json_encode($attributes));
        $payload['client'] = $data;

        $response = Http::withHeaders($this->headers())->post(config('tanda.flutterwave.base_url')
                .'/charges?type=card', $payload)
                ->json();

        if (Arr::get($response, 'status') == 'error') {
            abort(400, Arr::get($response, 'message'));
        }

        return $response;
    }

    public function validateCharge(array $attributes)
    {
        $response = Http::withHeaders($this->headers())->post(config('tanda.flutterwave.base_url')
            .'/validate-charge', $attributes)
            ->json();

        if (Arr::get($response, 'status') == 'error') {
            abort(400, Arr::get($response, 'message'));
        }
        return $response;
    }

    /**
     * @param string $transactionsId
     * @return array|object
     */
    public function verifyTransaction(string $transactionsId)
    {
        $response = Http::withHeaders($this->headers())->get(config('tanda.flutterwave.base_url').'/transactions/' .$transactionsId.'/verify');
        if ($response->ok()) {
            return $response->json();
        }
        abort(400, $response->json()['message']);
    }

    public function fundAccount(array $payload)
    {
        $response = Http::withHeaders($this->headers())->post(config('tanda.flutterwave.base_url').'/tokenized-charges', $payload);
        if ($response->ok()) {
            return $this->ok($response->json());
        }
        abort(400, $response->json()['message']);
    }

    /**
     * @param string $bvn
     * @return array|object
     */
    public function verifyBvn(string $bvn)
    {
        $response = Http::withHeaders($this->headers())->get(config('tanda.flutterwave.base_url') .'/kyc/bvns/'.$bvn);

        if ($response->ok()) {
            return $this->ok($response->json());
        }
        return $this->bad($response->json()['message']);
    }

    public function getBanks()
    {
        $response = Http::withHeaders($this->headers())->get(config('tanda.flutterwave.base_url').'/banks/NG/');
        if ($response->ok()) {
            return $this->ok($response->json());
        }
        return $this->bad($response->json()['message']);
    }

    public function resolveAccountDetails(array $attributes)
    {
        $response = Http::withHeaders($this->headers())->post(config('tanda.flutterwave.base_url').'/transfers', $attributes);
        if ($response->ok()) {
            return $this->ok($response->json());
        }
        abort(400, "Bank account not found");
    }

    public function createBankTransfer(array $attribute)
    {
        $response = Http::withHeaders($this->headers())->post(config('tanda.flutterwave.base_url').'/transfers', $attribute);
        if ($response->ok()) {
            return $this->ok($response->json());
        }
        abort(400, "Bank transfer failed");
    }

    private function headers(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.config('tanda.flutterwave.' . config('tanda.flutterwave.mode') . '.secret_key')
        ];
    }
}
