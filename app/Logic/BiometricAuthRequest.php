<?php


namespace App\Logic;

use App\Http\Requests\LoginFactoryRequest;
use App\Interfaces\ILogin;
use App\Models\User;
use App\Models\UserMobileCredential;
use App\Traits\ApiResponse;
use App\Traits\BiometricCredential;

class BiometricAuthRequest implements ILogin
{
    use ApiResponse;
    use BiometricCredential;

    private LoginFactoryRequest $request;

    public function __construct(LoginFactoryRequest $request)
    {
        $this->request = $request;
    }

    public function login()
    {
        $user = $this->getUser();
        if (empty($user)) {
            abort(400, 'Invalid credentials');
        }
        $mobileCredentials = $user->mobileCredential;
        $this->verifyHeader($mobileCredentials);
        if (!$this->verifySignature($user, $mobileCredentials->public_key)) {
            abort('Incorrect login credentials');
        }
        return $this->ok($user);
    }

    public function getUser()
    {
        return User::where('email', $this->request->email)
            ->whereNull('blocked_at')
            ->where('status', User::ENABLE)
            ->first();
    }

    /**
     * @param User $user
     * @param string $publicKey
     * @return bool
     */
    private function verifySignature(User $user, string $publicKey): bool
    {
        return $this->isSignatureValid($this->request->signature, $user->email, $publicKey);
    }

    /**
     * @param UserMobileCredential $userMobileCredential
     * @return bool
     */
    private function verifyHeader(UserMobileCredential $userMobileCredential): bool
    {
        if (empty($this->request->device_id)) {
            abort(400, 'Invalid login credentials');
        }

        if (is_null($userMobileCredential)) {
            abort(400, 'Invalid login credentials');
        }

        if ($userMobileCredential->device_id != $this->request->device_id) {
            abort(400, 'Could not identify the device the request is made from. Please use the device you registered with make this request or kindly request to change your device.');
        }
        return true;
    }
}
