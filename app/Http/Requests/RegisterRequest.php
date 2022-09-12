<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Traits\UtilityTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class RegisterRequest extends FormRequest
{
    use UtilityTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->phone_no = Str::startsWith($this->phone_no, '0') === true ? $this->phone_no :
            '0'.$this->phone_no;
        $this->merge([
            'phone_no' => $this->formatPhoneNumber($this->phone_no)
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:'.User::class.',email',
            'phone_no' => 'required|string|unique:'.User::class.',phone_no',
            'referral_code' => 'sometimes|exists:users,phone_no',
            'password' => 'required|string',
//            'phone_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|unique:users,phone_no',
        ];
    }
}
