<?php

namespace Modules\Circle\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Circle\Models\Circle;

class ChooseSlotRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'slot_number' => 'required|exists:circle_members,slot_number',
            'status' => [
                'required',
                Rule::in([Circle::PRIVATE, Circle::PUBLIC]),
            ]
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
