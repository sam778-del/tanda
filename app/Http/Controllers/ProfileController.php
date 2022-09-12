<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->successResponse("success", auth()->user());
    }

    public function updatePassword(UpdatePasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        auth()->user()->update([
            'password' => $request->new_password
        ]);
        return $this->successResponse("Password updated successfully");
    }

    public function transferLimits(): \Illuminate\Http\JsonResponse
    {
        $payload = [
            'daily_limit' => env('DAILY_TRANSFER_LIMIT'),
            'monthly_limit' => env('MONTHLY_TRANSFER_LIMIT')
        ];

        return $this->successResponse('success', $payload);
    }

    public function getSupport(): \Illuminate\Http\JsonResponse
    {
        $payload = [
            'contact_details' => env('SUPPORT_CONTACT') ?? null,
            'contact_email' => env('SUPPORT_EMAIL') ?? null,
            'contact_fb' => env('SUPPORT_FB') ?? null,
            'contact_twitter' => env('SUPPORT_TWITTER') ?? null,
            'contact_ig' => env('SUPPORT_IG') ?? null,
        ];

        return $this->successResponse('success', $payload);
    }

    public function trackReferrals()
    {
        $referrals = $this->profileService->getReferrals();
        if ($referrals->count() > 0) {

        }
    }
}
