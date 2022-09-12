<?php


namespace App\Logic;


use App\Http\Requests\LoginFactoryRequest;
use App\Http\Requests\LoginRequest;

class LoginFactory
{
    public static function create(LoginFactoryRequest $request)
    {
        if ($request->login_method === 'pin') {
            return (new PinAuthRequest($request))->login();
        }

        if ($request->login_method === 'biometric') {
            return (new BiometricAuthRequest($request))->login();
        }
        return (new PasswordAuthRequest($request))->login();
    }
}
