<?php

namespace Modules\Savings\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmartGoal extends Model
{
    use HasFactory, SoftDeletes;

    const ACTIVE = 'Active';
    const PAUSED = 'Paused';
    const LOCKED = 'Locked';

    protected $fillable = ['user_id','name','amount','deadline','duration', 'status','colour_code'];

    protected $dates = ['deadline'];

    protected $casts = [
        'deadline' => 'datetime',
        'can_delete' => 'boolean',
    ];

    public function savings_wallet(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SavingsWallet::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function walletHistory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SavingsWalletHistory::class)->where('status', true);
    }

    public function smartRules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SmartRule::class);
    }

    public function countSmartRules()
    {
        return $this->smartRules()->count();
    }

    public function goalBalance()
    {
        return $this->amount - $this->savings_wallet()->first()->actual_amount;
    }
}
