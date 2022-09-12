<?php

namespace App\Http\Requests\web;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Employer;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:'.Employer::class.',email',
            'password' => 'required|string',
            'know_us' => 'required|string'
        ];
    }
}
