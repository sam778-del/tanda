<?php

namespace Modules\Savings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmartGoalRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'amount' => 'required',
            'colour_code' => 'required',
//            'duration' => 'required|min:4|max:52',
            'deadline' => 'required|date',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
