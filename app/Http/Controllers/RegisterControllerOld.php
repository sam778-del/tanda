<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddEmailRequest;
use App\Http\Requests\AddPasswordRequest;
use App\Http\Requests\AddPhoneNumberRequest;
use App\Http\Requests\AddProfileRequest;
use App\Http\Requests\ResendCodeRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Requests\VerifyPhoneNumberRequest;
use App\Jobs\EmailOtpJob;
use App\Jobs\SmsOtpJob;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Arr;

class RegisterControllerOld extends Controller
{
    public AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Endpoint to add phone number
     * @param AddPhoneNumberRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPhoneNumber(AddPhoneNumberRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->authService->addPhone($request->validated());
        return $this->createdResponse('Phone number created successfully', $user->phone_no);
    }

    /**
     * Endpoint to verify phone number
     *
     * @param VerifyPhoneNumberRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyPhoneNumber(VerifyPhoneNumberRequest $request): \Illuminate\Http\JsonResponse
    {
        if (Arr::get($request->toArray(), 'user') != null) {
            return $this->successResponse('Phone number validated successfully', $request->user);
        }
        return $this->badRequestAlert('Validation failed');
    }

    /**
     * Endpoint to add email address
     *
     * @param AddEmailRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function addEmail(AddEmailRequest $request, User $user): \Illuminate\Http\JsonResponse
    {
        $this->authService->updateUser($user, $request->validated());
        $numberOfDigits = 4;
        $activationCode = substr(str_shuffle("0123456789"), 0, $numberOfDigits);
        EmailOtpJob::dispatch($user, $activationCode);
        return $this->createdResponse("Email added successfully");
    }

    /**
     * Endpoint to verify email
     *
     * @param VerifyEmailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyEmail(VerifyEmailRequest $request): \Illuminate\Http\JsonResponse
    {
        if (Arr::get($request->toArray(), 'user') != null) {
            return $this->successResponse('Email address validated successfully');
        }
        return $this->badRequestAlert('Validation failed');
    }

    /**
     * Endpoint to resend code to users phone or email
     * @param ResendCodeRequest $request
     */
    public function resendCode(ResendCodeRequest $request): \Illuminate\Http\JsonResponse
    {
        $numberOfDigits = 4;
        $activationCode = substr(str_shuffle("0123456789"), 0, $numberOfDigits);
        $request->field == 'email' ?
            EmailOtpJob::dispatch($request->user, $activationCode) :
            SmsOtpJob::dispatch($request->user, $activationCode);

        return $this->successResponse("Otp sent successfully");
    }

    public function addPassword(AddPasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->authService->updateUser($request->user, ['password' => $request->password]);
        return $this->createdResponse('Password added successfully', $request->user);
    }

    public function addProfile(AddProfileRequest $request): \Illuminate\Http\JsonResponse
    {
        $payload = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'dob' => $request->dob,
        ];
        $this->authService->updateUser($request->user, $payload);
        return $this->createdResponse('Profile added successfully');
    }
}
