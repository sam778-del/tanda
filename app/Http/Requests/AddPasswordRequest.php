<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AddPasswordRequest extends FormRequest
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
            'email' => 'bail|required|string|email|exists:users,email,status,' . User::DISABLE,
            'password' => 'required|string|confirmed|min:6',
        ];
    }

    public function withValidator($validator)
    {
        $user = User::whereEmail($this->email)->where('status', User::DISABLE)->first();
        if (empty($user)) {
            abort(400, 'Invalid credentials');
        }
        // verify the otp from user
        $this->merge(['user' => $user]);

    }
}
