<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Wallet\Models\UserCard;

class AddFundRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:100|max:1000000',
            'type' => 'required|in:card,bank'
        ];
    }

    public function prepareForValidation()
    {
        if ($this->type == 'card') {
            $activeCard = UserCard::query()
                ->where('user_id', auth()->user()->id)
                ->where('is_default', true)
                ->first();
            if (empty($activeCard)) {
                abort(400, "Please add a card first");
            }
            $this->merge([
                'card' => $activeCard,
            ]);
        }

        if ($this->type == 'bank') {
            abort_unless(auth()->user()->okraCredentials(), 400, "Please link your bank account with okra");
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
