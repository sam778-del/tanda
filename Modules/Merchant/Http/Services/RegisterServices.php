<?php


namespace Modules\Merchant\Http\Services;

use App\Models\ModelRole;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Merchant\Models\Merchant;

class RegisterServices
{
    /**
     * @param array $attributes
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     */
    public function storeMerchantInSession(array $attributes)
    {
        $merchant = new Merchant();
        $merchant->fill($attributes);
        return session(['merchant' => $merchant]);
    }

    /**
     * @param $request
     * @return mixed
     */
    public function validateMerchantOtp($request)
    {
        return Merchant::where('token', $request->token)->first();
    }

    /**
     * @return mixed
     */
    public function storeMerchantDetails()
    {
        $value = session('merchant');
        DB::transaction();
        try {
            $user = User::create([
                'phone' => $value['merchant']['phone'],
                'email' => $value['merchant']['email'],
                'password' => $value['merchant']['password'],
                'first_name' => $value['merchant']['first_name'],
                'last_name' => $value['merchant']['last_name']
            ]);

            ModelRole::assignRole('customer', $user->id);
            $user->merchant()->create([
                'user_id' => $user->id
            ]);

            Merchant::create([
                'business_name' => $value['merchant']['business_name'],
                'phone' => $value['merchant']['phone'],
                'business_phone' => $value['merchant']['business_phone'],
                'address' => $value['merchant']['address'],
                'city' => $value['merchant']['city'],
                'state' => $value['merchant']['state'],
                'business_sector_id' => $value['merchant']['business_sector_id'],
                'yearly_sales' => $value['merchant']['yearly_sales'],
                'company_reg_number' => $value['merchant']['company_reg_number'],
                'company_tax_number' => $value['merchant']['company_tax_number'],
                'website' => $value['merchant']['website'],
                'instagram_link' => $value['merchant']['instagram_link'],
                'facebook_link' => $value['merchant']['facebook_link'],
            ]);
            DB::commit();
            return $user;
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500, "Server error");
        }
    }
}
