<?php


namespace Modules\Savings\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Savings\Models\SavingsWallet;
use Modules\Savings\Models\SavingsWalletHistory;
use Modules\Savings\Models\SmartLock;
use Modules\Savings\Models\SmartSave;
use Modules\Savings\Models\SmartVest;

class SavingsService
{
    use ApiResponse;

    public function getUserSmartSave()
    {
        return SmartSave::query()
            ->where('user_id', auth()->user()->id)
            ->where('status', SmartSave::STATUS[0])
            ->get();
    }

    public function getUserSmartLock()
    {
        return SmartLock::query()
            ->where('user_id', auth()->user()->id)
            ->where('status', SmartLock::STATUS[0])
            ->get();
    }

    public function getUserSmartVest()
    {
        return SmartVest::query()->where('user_id', auth()->user()->id)
            ->where('status', SmartVest::STATUS[0])
            ->get();
    }

    public function withdrawAmount(float $amount, SavingsWallet $savingWallet, User $user)
    {
        return DB::transaction(function () use ($savingWallet, $user, $amount) {
            //Lock user wallet
            auth()->user()->lockWallet();
            auth()->user()->lockSavingWallet($savingWallet->id);

            $currentSavingsAmount = (float) $savingWallet->actual_amount;
            if($amount > $currentSavingsAmount)
            {
                abort(400, "Insufficient Amount");
            }

            $actualAmount = $currentSavingsAmount + $user->wallet->actual_amount;
            $walletUpdate = $user->wallet()->update(["initial_amount" => $user->wallet->actual_amount, "actual_amount" => $actualAmount  ]);

            $savingsActualAmount = $currentSavingsAmount - $amount;
            $payload = ["initial_amount" => $currentSavingsAmount, "actual_amount" => $savingsActualAmount ];

            $savingWallet->update($payload);
            
            $savingWalletHistory = SavingsWalletHistory::create();

            $transaction = $savingWalletHistory->transaction()->create([
                'user_id' => $payload['user_id'],
                'amount' => $payload['amount'],
                'status' => Transaction::SUCCESS,
                'transaction_ref' => Arr::get($payload, 'transaction_ref')
            ]);
        });

    }

}
