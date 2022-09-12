<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyTokenRequest extends FormRequest
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

    public function prepareForValidation()
    {
//        if (!$this->hasHeader('mono-webhook-secret')) {
//            abort(400, "Invalid header");
//        }
//        if ($this->header('mono-webhook-secret') != config("tanda.mono.secret")) {
//            abort(400, 'Invalid secret key mismatch');
//        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'code' => 'required',
            'email' => 'required|email|exists:users,email'
        ];
    }
}
