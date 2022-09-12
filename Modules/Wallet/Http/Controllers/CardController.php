<?php

namespace Modules\Wallet\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Wallet\Http\Requests\AddCardRequest;
use Modules\Wallet\Http\Requests\CardRequest;
use Modules\Wallet\Http\Requests\ValidateChargeRequest;
use Modules\Wallet\Models\UserCard;
use Modules\Wallet\Services\CardService;

class CardController extends Controller
{
    private CardService $cardService;

    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $cards = $this->cardService->getAllUserCards();
        return $this->successResponse('success', Arr::get($cards, 'data', []));
    }

    /**
     * @param UserCard $userCard
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(UserCard $userCard): \Illuminate\Http\JsonResponse
    {
        return $this->successResponse('success', optional($this->cardService->getSingleCard($userCard->id))->data);
    }

    /**
     * @param AddCardRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(AddCardRequest $request)
    {
        $card = $this->cardService->addCard($request->toArray());
        $response = [
            'tx_ref' => Arr::get($card, 'data.tx_ref'),
            'flw_ref' => Arr::get($card, 'data.flw_ref'),
            'amount' => Arr::get($card, 'data.amount'),
        ];

        return $this->createdResponse(Arr::get($card, 'data.processor_response'), $response);
    }

    /**
     * Validate the charge on the card when initiated
     * @param ValidateChargeRequest $request
     */
    public function validateCharge(ValidateChargeRequest $request): \Illuminate\Http\JsonResponse
    {
        $userCard = $this->cardService->validateChargeOnCard($request->toArray());
        return $this->createdResponse('Card created successfully', Arr::get($userCard, 'data'));
    }

    /**
     * @param CardRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(CardRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->cardService->deleteCard($request);
        return $this->successResponse('Card deleted successfully');
    }

    /**
     * @param CardRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeCardDefault(CardRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->cardService->makeCardDefault($request);
        return $this->successResponse('Default card set successfully');
    }
}
