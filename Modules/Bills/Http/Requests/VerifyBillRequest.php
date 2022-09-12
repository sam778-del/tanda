<?php

namespace Modules\Bills\Http\Requests;

use App\Traits\UtilityTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Bills\Models\Bill;

class VerifyBillRequest extends FormRequest
{
    use UtilityTrait;

    protected $stopOnFirstFailure = true;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'bill_id' => 'required|exists:bills,id,status,'.true,
            'msisdn' => 'required|string|min:10|max:1000000',
        ];

        if ($this->routeIs('bills.purchase')) {
            $rules['amount'] = 'required|numeric|min:50|max:100000';
        }

        return $rules;
    }

    public function prepareForValidation()
    {
        $bill = Bill::whereKey($this->bill_id)->first();
        if (empty($bill)) {
            abort(400, 'Bill not found');
        }
        $this->merge([
            'bill' => $bill,
        ]);

        if (in_array($this->bill->category, [
            Bill::MOBILE_DATA,
            Bill::AIRTIME,
        ])) {
            $this->merge([
                'msisdn' => $this->formatPhoneNumber($this->msisdn)
            ]);
        }
    }

    public function withValidator($validator)
    {
        $validator->sometimes('msisdn', 'phone:NG', function ($input) {
            return in_array($this->bill->category, [
                Bill::MOBILE_DATA,
                Bill::AIRTIME,
            ]);
        });

        if ($this->routeIs('bills.purchase')) {
            $validator->after(function ($validator) {
                if ((float) auth()->user()->wallet->actual_amount < $this->amount) {
                    $validator->errors()->add('amount', 'Insufficient fund, please fund your wallet');
                }
            });
        }

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

    public function messages()
    {
        return [
            'amount.min' => 'The amount must be at least :min.',
            'amount.max' => 'The amount must not be more than :max.',
            'msisdn.phone' => 'The number provided is not a valid phone number',
        ];
    }
}
