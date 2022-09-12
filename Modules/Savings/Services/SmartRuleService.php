<?php

namespace Modules\Savings\Services;

use App\Traits\ApiResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Savings\Models\SavingsWalletHistory;
use Modules\Savings\Models\SmartRule;

class SmartRuleService
{
    use ApiResponse;

    public function createRules(array $attribute)
    {
        DB::beginTransaction();
        try {
            $smart_rule = SmartRule::create([
                'user_id' => Arr::get($attribute, 'user_id'),
                'smart_goal_id' => Arr::get($attribute, 'smart_goal_id'),
                'type' => Arr::get($attribute, 'type'),
                'deduction_date' => Arr::get($attribute, 'deduction_date'),
                'amount' => Arr::get($attribute, 'amount'),
                'configuration' => $this->setConfiguration($attribute),
            ]);
            $user = $attribute['user_id'];
            $smart_goal = $attribute['smart_goal_id'];

            // loop through the wallet history
            DB::commit();
            return $this->ok($smart_rule);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->serverErrorAlert($exception->getMessage(), $exception);
        }
    }

    private function setConfiguration(array $attribute)
    {
        if ($attribute['type'] == 'Round-Up') {
            return json_encode([
                'round_up_amount' => $attribute['round_up_amount']
            ]);
        }

        if ($attribute['type'] == 'Payday') {
            return json_encode([
                'payday_percentage' => $attribute['payday_percentage'],
                'payday_deposit' => $attribute['payday_deposit'],
            ]);
        }

        if ($attribute['type'] == '52-Weeks-Rule') {
            return json_encode([
                '52_weeks_rule_order' => $attribute['52_weeks_rule_order'],
                '52_weeks_rule_save_on' => $attribute['52_weeks_rule_save_on'],
            ]);
        }
    }

    private function calculateRoundUpRule($amount, $nearestValue)
    {
        return (round(($amount+$nearestValue/2)/$nearestValue)*$nearestValue) - $amount;
    }

    private function calculatePayDayRule(int $percentage, int $amount)
    {
        return (($percentage / 100) * $amount);
    }

    private function calculateFiftyTwoWeeksRule(string $order, int $duration, int $amount)
    {
        $sum = 0;
        $sumArray = [];

        for ($i=0; $i<$duration; $i++) {
            $sum += $amount;
            $sumArray[] = $sum;
        }
        return $order == \Modules\Savings\Models\SmartRule::ASCENDING ? sort($sumArray) : rsort($sumArray);
    }
}
