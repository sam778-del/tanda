<?php

namespace Modules\Savings\Models;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SavingsWallet extends Model
{
    use HasFactory;

    protected $table = "savings_wallet";

    protected $fillable = ['user_id','smart_goal_id','initial_amount','actual_amount'];

    public function smart_goal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SmartGoal::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function savingWalletTransaction()
    {
        return $this->hasMany(SavingWalletTransaction::class);
    }
}
