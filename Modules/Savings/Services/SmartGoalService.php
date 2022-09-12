<?php

namespace Modules\Savings\Services;

use App\Models\Transaction;
use App\Services\OkraService;
use App\Traits\ApiResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Savings\Models\SavingsWalletHistory;
use Modules\Savings\Models\SavingWalletTransaction;
use Modules\Savings\Models\SmartGoal;
use Modules\Savings\Models\SmartRule;

class SmartGoalService
{
    use ApiResponse;

    public function createGoal(array $attribute)
    {
        // check if the user has linked his card to okra
        if (empty(auth()->user()->okraCredentials)) {
            abort(400, "Please link your card to okra");
        }

        $smart_goal = DB::transaction(function () use ($attribute) {
            $txRef = Transaction::generateRef();

            //Perform a direct debit on the user's bank account from okra
            $this->createDebit(Arr::get($attribute, 'amount'), Arr::get($attribute, 'deadline'));

            //create smart goal
            $smartGoal = SmartGoal::create($attribute);

            //create a wallet for the user
            $this->createSmartGoalWallet($smartGoal, (int) Arr::get($attribute, 'amount'), $txRef);

            // create a smart rule for set and forget
            $smartRule = $this->createDefaultRule($smartGoal, (int) Arr::get($attribute, 'amount'));

            //create saving wallet history
            $this->createSavingWalletHistory($smartGoal, $smartRule, (int) Arr::get($attribute, 'amount'), $txRef);
            return $smartGoal;
        });
        return $this->ok($smart_goal);
    }

    public function editGoalName(SmartGoal $smartGoal, string $goalName)
    {
        if ($smartGoal->user_id != auth()->user()->id) {
            abort(400, "Smart goal does not exist");
        }
        if ($smartGoal->status != SmartGoal::ACTIVE) {
            abort(400, "Smart goal is not active");
        }
        $smartGoal->update(['name' => $goalName]);
        return $this->ok($smartGoal);
    }

    private function isGoalActive(SmartGoal $smartGoal): bool
    {
        return $smartGoal->status == SmartGoal::ACTIVE ? true : false;
    }

    private function createDebit(int $amount, string $endDate)
    {
        $payload = [
            'account_to_debit' => '',
            'account_to_credit' => '',
            'type' => 'recurring',
            'schedule' => [
                'interval' => 'weekly',
                'startDate' => now()->toDateString(),
                'endDate' => $endDate,
            ],
            'amount' => $amount*100,
            'currency' => 'NGN',
        ];
        return (new OkraService())->debit($payload);
    }

    private function createSmartGoalWallet(SmartGoal $smartGoal, int $amount, string $txRef): \Illuminate\Database\Eloquent\Model
    {
        $savingWallet = $smartGoal->savings_wallet()->create([
            'user_id' => auth()->user()->id,
            'smart_goal_id' => $smartGoal->id,
            'initial_amount' => 0,
            'actual_amount' => $amount,
        ]);

        $savingTransaction = SavingWalletTransaction::createSavingsTransaction(
            $savingWallet->id,
            auth()->user()->id,
            $amount,
            $txRef,
            SavingWalletTransaction::SUCCESS,
            SavingWalletTransaction::TRANSACTION_TYPE['set-forget']
        );

        return $savingWallet->setRelation('saving_transaction', $savingTransaction);
    }

    private function createDefaultRule(SmartGoal $smartGoal, int $amount)
    {
        return SmartRule::create([
            'user_id' => auth()->user()->id,
            'smart_goal_id' => $smartGoal->id,
            'type' => 'Set-and-Forget',
            'deduction_date' => now(),
            'amount' => $amount,
        ]);
    }

    private function createSavingWalletHistory(
        SmartGoal $smartGoal,
        SmartRule $smartRule,
        int $amount,
        string $txRef
    ) {
        $savingWalletHistory = SavingsWalletHistory::create([
            'user_id' => auth()->user()->id,
            'smart_goal_id' => $smartGoal->id,
            'smart_rule_id' => $smartRule->id,
            'initial_amount' => 0,
            'actual_amount' => $amount,
            'status' => true
        ]);

        $transaction = $savingWalletHistory->transaction()->create([
            'user_id' => auth()->user()->id,
            'amount' => $amount,
            'status' => Transaction::SUCCESS,
            'transaction_ref' => $txRef
        ]);

        return $savingWalletHistory->setRelation('transaction', $transaction);
    }
}
