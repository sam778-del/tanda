<?php

namespace Modules\Merchant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MerchantContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'business_name' => 'required|min:3|unique:merchants,business_name',
            'business_phone' => 'required|min:3|unique:merchants,phone',
            'email' => 'required|min:3|unique:merchants,email',
            'address' => 'required|min:3',
            'city' => 'required|min:3',
            'state' => 'required|min:3',
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
