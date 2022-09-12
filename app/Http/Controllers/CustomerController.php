<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivateAccountRequest;
use App\Http\Requests\CreatePinCodeRequest;
use App\Http\Requests\ResendCodeRequest;
use App\Jobs\EmailOtpJob;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * @param ActivateAccountRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function activateAccount(ActivateAccountRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->userRepo->getUserByEmail($request->email);
        if (empty($user)) {
            return $this->badRequestAlert("User not found with the phone number provided");
        }

        //check user code from cache
        $cacheKey = $user->phone_no . "_activation_code";
        if (Cache::has($cacheKey)) {
            $cachedCode = Cache::get($cacheKey);
            if ($cachedCode != $request->code) {
                return $this->badRequestAlert("Invalid activation code");
            }
            $user->status = User::ENABLE;
            $user->save();
            Cache::forget($cacheKey);

            $tokenResult = $user->createToken('web-api-token')->accessToken;
            $payload = [
                'access_token' => $tokenResult,
                'user' => $user->load('customer'),
                'token_type' => 'Bearer',
            ];
            return $this->successResponse("User activated successfully", $payload);
        } else {
            return $this->badRequestAlert("Invalid activation code");
        }
    }

    /**
     * @param ResendCodeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendCode(ResendCodeRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $phone = $request->input("phone_no");
            $user = $this->userRepo->getUserByPhoneNo($phone);
            if ($user->status == User::ENABLE) {
                return $this->badRequestAlert("You cannot resend code to an active user");
            }

            $numberOfDigits = 4;
            $activationCode = substr(str_shuffle("0123456789"), 0, $numberOfDigits);
            EmailOtpJob::dispatch($user, $activationCode);
            DB::commit();
            return $this->successResponse("Activation code sent", $user);
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->serverErrorAlert('Unable to send activation code', $exception);
        }
    }
}
