<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginFactoryRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'login_method' => $this->login_method ?? 'password',
            'device_id' => $this->header('X-DEVICE-ID') ?? null,
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
            'login_method' => 'required|in:password,pin,signature',
            'email' => 'required|string|email',
            'password' => 'required_without_all:pin,signature|string|max:100',
            'pin' => 'required_without_all:password,signature|digits:4',
            'signature' => 'required_without_all:password,pin|string|max:255',
            'remember_me' => 'sometimes|boolean'
        ];
    }
}
