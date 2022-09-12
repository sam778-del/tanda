<?php


namespace App\Logic;


use App\Http\Requests\LoginFactoryRequest;
use App\Interfaces\ILogin;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;

class PinAuthRequest implements ILogin
{
    use ApiResponse;

    private LoginFactoryRequest $request;

    public function __construct(LoginFactoryRequest $request)
    {
        $this->request = $request;
    }

    public function login()
    {
        $user = $this->getUser();

        if (empty($user) || !$this->verifyPin($user)) {
            abort(400, 'Invalid credentials');
        }

        return $this->ok($user);
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return User::where('email', $this->request->email)
            ->whereNull('blocked_at')
            ->where('status', User::ENABLE)
            ->first();
    }

    /**
     * @param User $user
     * @return bool
     */
    private function verifyPin(User $user): bool
    {
        return Hash::check($this->request->pin, $user->customer->pin_code);
    }
}
