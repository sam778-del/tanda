<?php

namespace Modules\Savings\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SmartGoalResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user' => $this->user->only(['first_name', 'last_name', 'phone_no', 'email', 'dob', 'status', 'blocked_at', 'referral_code']),
            'wallet' => $this->savings_wallet->only(['initial_amount', 'actual_amount']),
            'wallet_history' => $this->walletHistory,
            'deadline' => $this->deadline ? $this->deadline->format("D, jS F Y") : null,
            'amount' => $this->amount,
            'colour_code' => $this->colour_code,
            'status' => $this->status,
            'smart_rules_count' => $this->countSmartRules(),
            'remaining_goal_balance' => $this->goalBalance()
        ];
    }
}
