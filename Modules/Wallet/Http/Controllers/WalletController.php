<?php

namespace Modules\Wallet\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\Wallet\Http\Requests\AddFundRequest;
use Modules\Wallet\Models\UserWallet;
use Modules\Wallet\Services\WalletService;

class WalletController extends Controller
{
    private $wallet;

    public function __construct()
    {
        $this->wallet = resolve(WalletService::class);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $walletAmount = $this->wallet->getBalance();
        if (!$walletAmount->status) {
            return $this->notFoundResponse('Wallet not found');
        }
        return $this->successResponse('success', $walletAmount->data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function walletHistory(): \Illuminate\Http\JsonResponse
    {
        $walletHistory = $this->wallet->getWalletHistory();
        return $this->successResponse('success', $walletHistory->data);
    }

    public function fundWallet(AddFundRequest $request)
    {
        $fund = $this->wallet->fundWallet($request);
        return $this->successResponse('success', $fund);
    }


}
