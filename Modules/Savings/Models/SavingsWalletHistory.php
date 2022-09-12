<?php

namespace Modules\Savings\Models;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SavingsWalletHistory extends Model
{
    use HasFactory;

    protected $table = "savings_wallet_history";

    protected $fillable = ['user_id','smart_goal_id','smart_rule_id','initial_amount','actual_amount','status'];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function smart_rules(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SmartRule::class);
    }

    public function smartGoal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SmartGoal::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Transaction::class, "transaction");
    }
}
