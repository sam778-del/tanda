<?php

namespace Modules\Savings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Savings\Models\SmartLock;

class SmartLockRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|min:1000|max:500000',
            'name' => 'required|string|min:3|unique:smart_locks,name',
            'smart_lock_duration_id' => 'required|exists:smart_lock_durations,id',
            'payment_method' => 'required|in:'.implode(',', SmartLock::PAYMENT_MODE)
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
