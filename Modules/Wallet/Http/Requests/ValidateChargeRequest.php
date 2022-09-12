<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateChargeRequest extends FormRequest
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

    public function rules(): array
    {
        return [
            'otp' => 'required',
            'flw_ref' => 'required',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'type' => 'card'
        ]);
    }
}
