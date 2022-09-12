<?php

namespace Modules\Wallet\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserWallet extends Model
{
    use HasFactory;

    protected $fillable = ["user_id","initial_amount","actual_amount"];

    protected static function newFactory()
    {
        return \Modules\Wallet\Database\factories\UserWalletFactory::new();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function updateUserWallet(User $user, float $amount)
    {
        $userWallet = $user->wallet;
        $userWallet->initial_amount = $userWallet->actual_amount;
        $userWallet->actual_amount = $userWallet->actual_amount - $amount;
        $userWallet->save();
    }
}
