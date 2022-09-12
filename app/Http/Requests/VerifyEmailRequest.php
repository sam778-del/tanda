<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Traits\UtilityTrait;
use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|exists:users,email',
            'otp' => 'required|string|max:4'
        ];
    }

    public function withValidator($validator)
    {
        $user = User::whereEmail($this->email)->where('status', User::DISABLE)->first();
        if (empty($user)) {
            abort(400, 'Invalid credentials');
        }
        // verify the otp from user
        if ($this->verifyOtpFromUser($user, '_activation_code', $this->otp)) {
            $this->merge(['user' => $user]);
        }
    }
}
