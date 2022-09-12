<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AddProfileRequest extends FormRequest
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
            'email' => 'bail|required|string|exists:users,email|email|status,' . User::DISABLE,
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'dob' => 'required|date_format:Y-m-d'
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
