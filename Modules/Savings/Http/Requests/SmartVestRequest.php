<?php

namespace Modules\Savings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Savings\Models\SmartLock;
use Modules\Savings\Models\SmartVest;

class SmartVestRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|min:1000|max:500000',
            'duration' => 'required||min:3|max:12',
            'initial_payment' => 'required|min:1000',
            'payment_method' => 'required|in:'.implode(',', SmartVest::PAYMENT_MODE)
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
