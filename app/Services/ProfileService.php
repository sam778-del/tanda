<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Collection;

class ProfileService
{
    use ApiResponse;

    public function getReferrals()
    {
        return User::query()
            ->where('referrer_id', auth()->user()->id)
            ->get();
    }

    public function formatReferrals(Collection $attributes)
    {
        $users = [];

        foreach ($attributes as $key => $user) {
            $users[$key] = [
                'first_name' => $user->first_name,
                'created_at' => $user->created_at->format("D, jS F Y"),
            ];
        }
    }

    private function getProgress(User $user)
    {
        if (is_null($user->first_name)) {
            return 1;
        }
        if (empty($user->okraCredentials)) {
            return 2;
        }
    }
}
