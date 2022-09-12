<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
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
            'old_password' => 'required|string',
            'new_password' => ['required','string', Password::min(8)
                ->letters()
                ->numbers()
                ->mixedCase()],
        ];
    }

    public function withValidator($validator)
    {
        if (!$this->verifyPassword()) {
            abort(400, 'Invalid password credentials');
        }
    }

    private function verifyPassword(): bool
    {
        return Hash::check($this->old_password, auth()->user()->password);
    }
}
