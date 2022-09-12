<?php

namespace Modules\Merchant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MerchantDetailsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'business_sector_id' => 'required|exists:business_sectors,id',
            'yearly_sales' => 'required',
            'company_reg_number' => 'required',
            'company_tax_number' => 'nullable',
            'website' => 'required',
            'instagram_link' => 'nullable',
            'facebook_link' => 'nullable',
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
