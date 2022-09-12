<?php

namespace App\Services;

use App\Jobs\SmsOtpJob;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Arr;

class AuthService
{
    use ApiResponse;

    public function addPhone(array $attributes)
    {
        $user = User::where('phone_no', Arr::get($attributes, 'phone_no'))
            ->where('status', User::DISABLE)
            ->first();

        if (empty($user)) {
            $user = User::create($attributes);
        }

        $numberOfDigits = 5;
        $activationCode = substr(str_shuffle("0123456789"), 0, $numberOfDigits);
        SmsOtpJob::dispatch($user, $activationCode);

        return $user;
    }

    public function updateUser(User $user, array $attributes)
    {
        return $user->update($attributes);
    }

    /**
     * Create a user
     *
     * @param array $attribute
     * @return mixed
     */
    public function registerUser(array $attribute)
    {
        return User::create([
            'first_name' => Arr::get($attribute, 'first_name'),
            'last_name' => Arr::get($attribute, 'last_name'),
            'email' => Arr::get($attribute, 'email'),
            'phone_no' => Arr::get($attribute, 'phone_no'),
        ]);
    }
}
