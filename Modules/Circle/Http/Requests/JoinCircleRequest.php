<?php

namespace Modules\Circle\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Circle\Models\CircleMember;

class JoinCircleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'slot_number' => 'required',
//            'circle_id' => 'required|exists:circles,id',
            'payment_method' => 'required|in:'.implode(',', CircleMember::PAYMENT_METHOD)
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
