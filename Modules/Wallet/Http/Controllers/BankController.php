<?php

namespace Modules\Wallet\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Wallet\Http\Requests\AddBankAccount;
use Modules\Wallet\Http\Requests\PayoutRequest;
use Modules\Wallet\Models\UserBankAccount;
use Modules\Wallet\Services\BankService;

class BankController extends Controller
{

    protected $bank_service;

    public function __construct(BankService $bank_service)
    {
        $this->bank_service = $bank_service;
    }


    public function index()
    {
        $banks = UserBankAccount::query()->where('user_id', auth()->user()->id)->get();
        return $this->ok($banks);
    }

    public function get_banks()
    {
        return $this->successResponse('List of banks', $this->bank_service->getBanks());
    }

    public function add_bank_account(AddBankAccount $request): \Illuminate\Http\JsonResponse
    {
        $bankDetails = $request->validated();
        $bankDetails['user_id'] = $request->user_id;
        //create bank details record for customer
        return $this->successResponse('success', $this->bank_service->saveCustomerBank($bankDetails));
    }

    public function payout_request($bankid, PayoutRequest $request): \Illuminate\Http\JsonResponse
    {
        $payoutRequest = $request->validated();
        $payoutRequest['user_id'] = $request->user_id;

        $payoutResponse =  $this->bank_service->processPayout($bankid, $payoutRequest);

        return $this->successResponse('Payout processed successfully');
    }
}
