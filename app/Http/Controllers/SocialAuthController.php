<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    use ApiResponse;

    public function redirect($service): \Illuminate\Http\JsonResponse
    {
        if (!in_array($service, [User::FACEBOOK, User::GOOGLE])) {
            return $this->badRequestAlert('Service currently not available');
        }
        $url = Socialite::driver($service)
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return $this->successResponse('Redirect url sent successfully', $url);
    }

    /**
     * @param $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback($service): \Illuminate\Http\JsonResponse
    {
        $socialUser = Socialite::driver($service)->stateless()->user();
        $this->findOrCreate($service, $socialUser);

//        $token = $user->createToken('web-api-token')->accessToken;
//        $payload = $this->generatePayload($token, $user);
        return $this->successResponse('User created successfully');
    }

    /**
     * @param $service
     * @param $provider
     * @return mixed
     */
    private function findOrCreate($service, $provider)
    {
        $account = Customer::where('provider_name', $service)
            ->where('provider_id', $provider->getId())
            ->first();

        if ($account) {
            return $account->user;
        } else {
            $user = User::whereEmail($provider->getEmail())->first();

            if (! $user) {
                $user = User::create([
                    'email' => $provider->getEmail(),
                    'first_name'  => $provider->getName(),
                    'last_name'  => $provider->getName(),
                ]);
            }

            $user->customer()->create([
                'provider_id'   => $provider->getId(),
                'provider_name' => $service,
            ]);

            return $user;
        }
    }

    /**
     * @param $token
     * @param $user
     * @return array
     */
    private function generatePayload($token, $user): array
    {
        return [
            'access_token' => $token,
            'user' => $user,
            'token_type' => 'Bearer',
        ];
    }
}
