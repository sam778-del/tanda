<?php


namespace App\Services;

use App\Http\Requests\LoginFactoryRequest;
use App\Logic\LoginFactory;
use App\Models\User;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AuthServiceOld
{
    use ApiResponse;

    /**
     * @param array $attributes
     * @return array
     */
    public function createUser(array $attributes): array
    {
        $user =  DB::transaction(function () use ($attributes) {
            $user = User::create($attributes);
            $user->customer()->create([
                'user_id' => $user->id,
            ]);

            //create a wallet for the user
            $user->wallet()->create([
                'user_id' => $user->id,
                'initial_amount' => 0,
                'actual_amount' => 0,
            ]);
        });


        return $this->ok($user);
    }

    /**
     * @param string $referralCode
     * @return array
     */
    public function getReferral(string $referralCode): array
    {
        $referral = User::query()->where("phone_no", $referralCode)->first();
        if (empty($referral)) {
            return $this->bad();
        }
        return $this->ok($referral);
    }

    /**
     * @param array $input
     * @return array
     */
    public function credentials(array $input): array
    {
        $email = $input["email"];
        $password = $input["password"];

        $fieldType = filter_var($email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_no';
        return [
            $fieldType => $email,
            'password' => $password
        ];
    }

    public function login(array $input)
    {
        $credentials = $this->credentials($input);
        $user = auth()->attempt($credentials);
        if (!$user) {
            abort(400, 'Invalid credentials');
        }

        $user = User::query()
            ->orWhere('email', $input['email'])
            ->orWhere('phone_no', $input['email'])
            ->first();

        if ($user->status == User::DISABLE) {
            abort(400, "Account is still disabled");
        }

        if (!is_null($user->blocked_at)) {
            abort(400, "Account is blocked");
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
//        if ($input['remember_me']) {
//            $token->expires_at = Carbon::now()->addWeeks(1);
//        }
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        $payload =  [
            'token' => $tokenResult->accessToken,
            'user' => $user,
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ];
        return $this->ok($payload);
    }

    public function loginNew(LoginFactoryRequest $request)
    {
        $user = LoginFactory::create($request);
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        $payload =  [
            'token' => $tokenResult,
            'user' => $user,
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ];
        return $this->ok($payload);
    }
}
