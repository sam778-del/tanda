<?php


namespace App\Logic;


use App\Http\Requests\LoginFactoryRequest;
use App\Interfaces\ILogin;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;

class PasswordAuthRequest implements ILogin
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
        if (empty($user) || !$this->verifyPassword($user)) {
            abort(400, 'Invalid credentials');
        }
        return $this->ok($user);
    }

    public function getUser()
    {
        return User::query()
            ->where('status', User::ENABLE)
            ->whereNull('blocked_at')
            ->orWhere('email', $this->request->email)
            ->orWhere('phone_no', $this->request->email)
            ->first();
    }

    private function verifyPassword(User $user): bool
    {
        return Hash::check($this->request->password, $user->password);
    }
}
