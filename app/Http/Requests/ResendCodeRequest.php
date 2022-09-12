<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ResendCodeRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'auth_input' => 'required|string',
        ];
    }

    public function withValidator($validator)
    {
        $field = filter_var($this->auth_input, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_no';
        if ($field == 'email') {
            $user = User::whereEmail($this->auth_input)->where('status', User::DISABLE)->first();
        } else {
            $user = User::wherePhoneNo($this->auth_input)->where('status', User::DISABLE)->first();
        }

        $this->merge([
            'user' => $user,
            'field' => $field
        ]);

    }
}
