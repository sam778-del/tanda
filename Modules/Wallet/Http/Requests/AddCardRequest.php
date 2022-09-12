<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class AddCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'card_number' => 'required|min:11',
            'cvv' => 'required|min:3',
            'expiry_month' => 'required|min:2',
            'expiry_year' => 'required|min:2',
            'amount' => 'required',
            'authorization' => 'required|array',
            'authorization.*.mode' => 'required|string',
            'authorization.*.pin' => 'required|string',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'currency' => 'NGN',
            'email' => auth()->user()->email,
            'tx_ref' => Str::random(11),
        ]);
    }


}
