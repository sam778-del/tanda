<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Repositories\UserRepository;
use App\Services\AuthServiceOld;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{
    use ApiResponse;

    public $userRepo;

    public AuthServiceOld $authService;

    public function __construct(UserRepository $userRepo, AuthServiceOld $authService)
    {
        $this->userRepo = $userRepo;
        $this->authService = $authService;
    }

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->validated();
        $credentials = $this->authService->login($input);
        if (!$credentials['status']) {
            return $this->badRequestAlert('Invalid username or password');
        }
        $response = [
            'user' => Arr::get($credentials, 'data.user'),
            'access_token' => Arr::get($credentials, 'data.token'),
            'token_type' => 'Bearer',
            'expires_at' => Arr::get($credentials, 'data.expires_at')
        ];
        return $this->successResponse("login successful", $response);
    }


    /**
     * Logout user (Revoke the token)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse [string] message
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->token()->revoke();
        return $this->successResponse('Successfully logged out');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(): \Illuminate\Http\JsonResponse
    {
        \request()->validate([
            "email" => "required|email|exists:users,email"
        ], [
            "email.exists" => "No user found with the email supplied"
        ]);
        $email = \request()->input("email");
        $user = User::query()->where("email", $email)->first();

        if (!is_null($user) and $user->status == "DISABLE") {
            return $this->badRequestAlert("User not active, you cannot reset password");
        }

        $numberOfDigits = 5;
        $token = substr(str_shuffle("0123456789"), 0, $numberOfDigits);

        DB::table("password_resets")
            ->insert([
                "email" => $user->email,
                "token" => $token,
                "created_at" => \Carbon\Carbon::now()
            ]);

//        $message = "Dear {$user->first_name}, copy this code: {$token} to reset your password" . PHP_EOL;

        $mailData = (object) [
            'subject' => "Password Rest From Tanda",
            'body' => "Dear {$user->first_name}, copy this code: {$token} to reset your password" . PHP_EOL,
            'user' => $user
        ];

        //return $message;
        Notification::route('mail', $user->email)
            ->notify(new PasswordResetNotification($mailData));

        return $this->successResponse("A password reset link has been sent to your email");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userResetPassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->validate([
            "token" => "required|exists:password_resets,token",
            "password" => "required|confirmed|min:6",
            "password_confirmation" => "required|min:6"
        ]);

        $token = $request->input("token");

        $userToken = PasswordReset::query()->where("token", $token)->first();

        if (empty($userToken)) {
            return $this->badRequestAlert("No User with the token supplied");
        }

        if (now()->gt(Carbon::parse($userToken->created_at)->addHours(1))) {
            return $this->badRequestAlert("Token Expire");
        }

        $user = User::query()->where("email", $userToken->email)->first();
        if (empty($user)) {
            return $this->badRequestAlert("No User with the token supplied");
        }

        $user->password = $request->input("password");
        $user->save();

        //remove password reset
        PasswordReset::query()->where("token", $token)->delete();
        return $this->successResponse("Password Successfully changed", $user);
    }
}
