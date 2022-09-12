<?php


namespace Modules\Wallet\Services;

use App\Logic\Flutterwave;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Wallet\Http\Requests\CardRequest;
use Modules\Wallet\Models\UserCard;

class CardService
{
    use ApiResponse;

    public function __construct()
    {
    }

    public function getAllUserCards()
    {
        $cards = UserCard::query()
            ->where('user_id', auth()->user()->id)
            ->get();

        return $this->ok($cards);
    }

    public function getSingleCard(int $id)
    {
        $card = UserCard::where('user_id', auth()->user()->id)->find($id);
        if (empty($card)) {
            throw new ModelNotFoundException('Card not found');
        }
        return $this->ok($card);
    }

    public function addCard(array $attribute)
    {
        $payload = Arr::except($attribute, 'authorization');
        $transaction = DB::transaction(function () use ($attribute, $payload) {
            $flutterwaveService = resolve(Flutterwave::class);
            $flutterwave = $this->initiatePayment($flutterwaveService, $payload);
            if (Arr::get($flutterwave, 'meta.authorization.mode') == 'pin' &&
                Arr::get($flutterwave, 'status') == 'success') {
                //initiate another payment
                $payload = array_merge($payload, ['authorization' => [
                    'mode' => Arr::get($flutterwave, 'meta.authorization.mode'),
                    'pin' => Arr::get($attribute, 'authorization.0.pin')
                ]]);

                $initiateCharge = $this->initiatePayment($flutterwaveService, $payload);

                // Validate charge on card
                if (Arr::get($initiateCharge, 'status') == 'success') {
                    return Arr::get($initiateCharge, 'data');
                }
            }
            throw new Exception(Arr::get($flutterwave, 'message'));
        });
        return $this->ok($transaction);
    }

    public function validateChargeOnCard(array $attributes)
    {
        $transaction = DB::transaction(function () use ($attributes) {
            $flutterwaveService = resolve(Flutterwave::class);
            $response = $flutterwaveService->validateCharge($attributes);

            if (Arr::get($response, 'status') == 'success') {
                // verify transaction from flutterwave
                $verifyTransaction = $flutterwaveService->verifyTransaction(Arr::get($response, 'data.id'));
                if (Arr::get($verifyTransaction, 'status') == 'success' && Arr::get($verifyTransaction, 'data.status') == 'successful') {
                    $userCard = $this->getDefaultUserCard();
                    if (!empty($userCard)) {
                        $userCard->update([
                            'is_default' => false
                        ]);
                    }
                    $payload = Arr::get($verifyTransaction, 'data.card');
                    return UserCard::create([
                        "user_id" => auth()->user()->id,
                        "first_6digits" => $payload['first_6digits'],
                        "last_4digits" => $payload['last_4digits'],
                        "issuer" => $payload['issuer'],
                        "country" => $payload['country'],
                        "type" => $payload['type'],
                        "token" => $payload['token'],
                        "expiry" => $payload['expiry'],
                        "is_default" => true,
                    ]);
                }
            }
        });

        return $this->ok($transaction);
    }

    public function deleteCard(CardRequest $request)
    {
        $userCard = UserCard::where('user_id', auth()->user()->id)->find($request->card_id);
        if ($userCard->is_default == true) {
            abort(400, 'You cannot delete your default card');
        }
        $userCard->delete();
        return $this->ok("Card deleted successfully");
    }

    public function makeCardDefault(CardRequest $request)
    {
        $card = DB::transaction(function () use ($request) {
            $oldCard = UserCard::where('user_id', auth()->user()->id)
                ->where('is_default', true)
                ->first();

            if (!empty($oldCard)) {
                $oldCard->update([
                    'is_default' => false
                ]);
            }

            $userCard = UserCard::where('user_id', auth()->user()->id)
                ->find($request->card_id);

            $userCard->is_default = true;
            $userCard->save();

            return $userCard;
        });

        return $this->ok("success", $card);
    }

    private function initiatePayment(Flutterwave $flutterwave, array $attribute)
    {
        return $flutterwave->initiatePayment($attribute);
    }

    public function getDefaultUserCard()
    {
        return UserCard::where('user_id', auth()->user()->id)->where('is_default', true)->first();
    }
}
