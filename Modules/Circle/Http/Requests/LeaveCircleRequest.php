<?php

namespace Modules\Circle\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveCircleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'leave_circle_message_id' => 'required|exists:leave_circle_messages,id'
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
