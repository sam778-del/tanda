<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddBankAccount extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "account_number" => "required|string|max:10",
            "account_name" => "required|string",
            "bank_code" => "required|string|max:6"
        ];
    }


    public function withValidator()
    {
        $this->merge([
            'user_id' => auth()->user()->id,
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
