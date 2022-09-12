<?php


namespace App\Traits;

use App\Mail\UserOtp;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

trait UtilityTrait
{
    public function getWalletBalance()
    {
        return auth()->user()->wallet->actual_amount;
    }

    public function sendOtpToUser(User $user, string $prefix, $mail = false)
    {
        $numberOfDigits = 4;
        $activationCode = substr(str_shuffle("0123456789"), 0, $numberOfDigits);

        if ($mail) {
            $data = [
                'subject' => 'One Time Password to Change your Mobile Device',
                'body' => 'Here is your One Time Password (OTP) to complete the reset of your mobile device so you can access the Tanda App. It will expire in 5 minutes.',
            ];

            //Sends the user an Email using the Email Helper method on b uy card request
            Mail::to($user->id)->send(new UserOtp($activationCode, $user, $data));
        }

        Cache::put($user->id . $prefix, $activationCode, now()->addMinutes(15));
    }

    public function verifyOtpFromUser(User $user, string $prefix, string $otpCode)
    {
        $cacheKey = $user->id . $prefix;

        if (Cache::has($cacheKey)) {
            $cachedCode = Cache::get($cacheKey);
            if ($cachedCode != $otpCode) {
                abort(400, "Invalid Otp code");
            }
            Cache::forget($cacheKey);
            return true;
        }
        abort(400, 'Invalid token');
    }

    public function formatPhoneNumber($destination)
    {
        if ($destination === '') {
            return false;
        }

        $destination = str_replace(" ", ',', $destination);
        $destination = str_replace("\n", ',', $destination);
        $destination = str_replace("\r", ',', $destination);
        $destination = str_replace(",,", ',', $destination);

        $explode = explode(',', $destination);
        $explode = array_unique($explode);

        $test = array();

        foreach ($explode as $value) {
            # code...
            if (substr($value, 0, 3) == '009') {
                # code...
                $value = substr($value, 3);
                $test[] = $value;
            } elseif (substr($value, 0, 1) == '+') {
                # code...
                $value = substr($value, 1);
                $test[] = $value;
            } elseif (substr($value, 0, 1) == '0') {
                # code...
                //$test[] = preg_replace('/0/', '234', $value, 1);
                $value = '234' . substr($value, 1);
                $test[] = $value;
            } elseif (substr($value, 0, 3) == '234') {
                # code...
                $test[] = $value;
            }
        }
        return implode(',', $test);
    }
}
