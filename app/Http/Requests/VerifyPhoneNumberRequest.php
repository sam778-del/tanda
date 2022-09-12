<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Traits\UtilityTrait;
use Illuminate\Foundation\Http\FormRequest;

class VerifyPhoneNumberRequest extends FormRequest
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
        $this->merge([
            'phone_no' => $this->formatPhoneNumber($this->phone_no)
        ]);
    }

    public function withValidator($validator)
    {
        $user = User::wherePhoneNo($this->phone_no)->where('status', User::DISABLE)->first();
        if (empty($user)) {
            abort(400, 'Invalid credentials');
        }
        // verify the otp from user
        if ($this->verifyOtpFromUser($user, '_activation_code', $this->otp)) {
            $this->merge(['user' => $user]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'phone_no' => 'required|string|exists:users,phone_no|phone:NG',
            'otp' => 'required|string|max:5'
        ];
    }


}
