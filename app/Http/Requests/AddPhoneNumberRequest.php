<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Traits\UtilityTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class AddPhoneNumberRequest extends FormRequest
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
        $this->phone_no = Str::startsWith($this->phone_no, '0') === true ? $this->phone_no :
            '0'.$this->phone_no;
        $this->merge([
            'phone_no' => $this->formatPhoneNumber($this->phone_no)
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'phone_no' => 'required|string|phone:NG|digits:13',
        ];
    }

    private function formatNumber($number)
    {
        return Str::startsWith($number, '0');
    }
}
