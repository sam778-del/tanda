<?php

namespace Modules\Savings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Modules\Savings\Models\SmartGoal;
use Modules\Savings\Models\SmartRule;

class SmartRuleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = collect(SmartRule::RULE_TYPE)->transform(function ($value) {
            return $value['name'];
        })->all();

        return [
            'smart_goal_id' => 'required|integer|exists:smart_goals,id,user_id,'.auth()->user()->id.',status,'.SmartGoal::ACTIVE,
            'type' => 'required|string|in:'.implode(',', $rules),
            'amount' => 'required',
            'round_up_amount' => [
                Rule::requiredIf($this->type == 'Round-Up'),
                'numeric',
                'min:100',
                'max:1000'
            ],
            'payday_percentage' => [
                Rule::requiredIf($this->type == 'Payday'),
                'numeric',
                'min:10',
                'max:100'
            ],
            'payday_deposit' => [
                Rule::requiredIf($this->type == 'Payday'),
                'numeric',
                'min:5000',
                'max:100000'
            ],
            '52_weeks_rule_order' => [
                Rule::requiredIf($this->type == '52-Weeks-Rule'),
                Rule::in([SmartRule::ASCENDING, SmartRule::DESCENDING]),
            ],
            '52_weeks_rule_save_on' => [
                Rule::requiredIf($this->type == '52-Weeks-Rule'),
                'numeric',
                'min:1',
                'max:52'
            ],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
