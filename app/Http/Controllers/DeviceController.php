<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddDeviceRequest;
use App\Http\Requests\ChangeDeviceRequest;
use App\Mail\UserOtp;
use App\Models\User;
use App\Models\UserMobileCredential;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DeviceController extends Controller
{
    /**
     * @param ChangeDeviceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestToChange(ChangeDeviceRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::whereNull('blocked_at')
            ->where('status', User::ENABLE)
            ->where('email', $request->email)
            ->first();

        if (empty($user)) {
            return $this->badRequestAlert('The email address is not valid');
        }

        DB::beginTransaction();
        try {
            $this->sendOtpToUser($user, '_device_token');
            DB::commit();
            return $this->successResponse("An OTP code has been sent to your your email address");
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->serverErrorAlert('Unable to send activation code', $exception);
        }
    }

    /**
     * @param AddDeviceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addDevice(AddDeviceRequest $request): \Illuminate\Http\JsonResponse
    {
        $cacheKey = $request->email . "_device_token";
        if (Cache::has($cacheKey)) {
            $cachedCode = Cache::get($cacheKey);
            if ($cachedCode != $request->otp_code) {
                return $this->badRequestAlert("Invalid Otp code");
            }
            $user = User::where('email', $request->email)->first();

            // Update or create the user device credentials
            UserMobileCredential::updateOrCreate([
                'user_id' => $user->id
            ], [
                'device_id' => $request->device_id,
                'public_key' => null
            ]);

            return $this->createdResponse("Your device credentials has been added successfully");
        } else {
            return $this->badRequestAlert("Invalid Otp code");
        }
    }
}
