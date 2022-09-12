<?php

namespace App\Http\Controllers;

use App\Http\Requests\BiometricRequest;
use App\Http\Requests\DisableBiometricRequest;
use App\Models\User;
use App\Traits\BiometricCredential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BiometricController extends Controller
{
    use BiometricCredential;

    /**
     * Store biometric credentials of the user
     *
     * @param BiometricRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BiometricRequest $request): \Illuminate\Http\JsonResponse
    {
        if (!$this->isPublicKeyValid($request->public_key)) {
            return $this->badRequestAlert('Invalid biometric key');
        }
        $user = User::where('email', $request->email)->first();

        $user->mobileCredential->update([
            'public_key' => $this->formatPublickey($request->public_key)
        ]);

        return $this->createdResponse('Biometric credentials has been enabled...');
    }

    /**
     * Disable users biometric credentials
     *
     * @param DisableBiometricRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable(DisableBiometricRequest $request): \Illuminate\Http\JsonResponse
    {
        if (! Hash::check($request->password, auth()->user()->password)) {
            return $this->badRequestAlert('Invalid password provided. Kindly try again.');
        }

        auth()->user()->mobileCredential->update([
            'public_key' => null
        ]);

        return $this->createdResponse('Biometric credentials has been disabled...');
    }
}
