<?php

namespace Modules\Wallet\Services;

use App\Logic\Flutterwave;
use App\Traits\ApiResponse;
use Illuminate\Support\Arr;
use Modules\Wallet\Models\UserBankAccount;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Modules\Wallet\Models\UserBankAccountTransaction;

class BankService
{
    use ApiResponse;

    protected $flutterwaveService;
    
    public function __construct(Flutterwave $flutterwaveService)
    {
        $this->flutterwaveService = $flutterwaveService;
    }

    public function saveCustomerBank(array $attribute)
    {
        //resolve account details to make sure it's valid before proceeding
        $account_resolve = [];
        $account_resolve['account_number'] = $attribute['account_number'];
        $account_resolve['bank'] = $attribute['bank_code'];

        $resolvedAccount = $this->flutterwaveService->resolveAccountDetails($account_resolve);

        if(Arr::get($resolvedAccount, 'status') == 'success' && Arr::get($resolvedAccount, 'message') == 'Account details fetched')
        {
            $createdBank = UserBankAccount::create($attribute);
            return $this->ok($createdBank);
        }
        throw new \HttpException(Arr::get($resolvedAccount, 'message'));
    }

    public function getBanks()
    {
        $banks = $this->flutterwaveService->getBanks();
        if(Arr::get($banks, 'status') == 'success')
        {
            return $this->ok($banks);
        }
        return $banks['data'];
    }

    public function processPayout(int $bankid, array $attribute)
    {
        //get bank details based on provided bankid
        $bankDetails = UserBankAccount::where([['user_id','=', $attribute['user_id']], ['id','=', $bankid]])->first();
        if($bankDetails)
        {
            //process payout to specified account then process wallet deduction for same amount
            $payout_details = [];
            $payout_details['account_bank'] = $bankDetails['bank_code'];
            $payout_details['account_number'] = $bankDetails['account_number'];
            $payout_details['amount'] = $attribute['payout_amount'];
            $payout_details['narration'] = "Tanda Wallet Payout";
            $payout_details['currency'] = "NGN";
            $payout_details['reference'] = $attribute['user_id'];
            $payout_details['debit_currency'] = "NGN";

            //do wallet deduction here before proceeding
            $transaction = DB::transaction(function () use ($payout_details,$bankDetails) {
                $processPayout = $this->flutterwaveService->createBankTransfer($payout_details);

                if (Arr::get($processPayout, 'status') == "success" && Arr::get($processPayout, 'message') == 'Transfer Queued Successfully') {
                    //insert record in user bank account traansaction table
                    return UserBankAccountTransaction::create([
                        "user_id"=> $payout_details['user_id'],
                        "user_bank_account_id"=> $bankDetails['id'],
                        "transfer_id"=> $processPayout['data']['id'],
                        "amount"=> $payout_details['amount'],
                        "reference"=> $processPayout['data']['reference'],
                        "status"=> true
                    ]);
                }
                throw new \HttpException(Arr::get($processPayout, 'message'));
            });
            return $this->ok($transaction);
        }else{
            abort(400, 'Invalid bank selected');
        }
    }

}
