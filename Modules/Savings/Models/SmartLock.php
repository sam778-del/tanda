<?php

namespace Modules\Savings\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Models\SmartLockDuration;

class SmartLock extends Model
{
    use HasFactory;

    const STATUS = ['Active', 'Completed'];
    const PAYMENT_MODE = ['Wallet', 'Card'];

    protected $fillable = [
        "name",
        "amount",
        "interest",
        "user_id",
        "lock_duration",
        "payout_date",
        "payment_method"
    ];

    protected $appends = ['interest_to_earn'];

    protected $casts = [
        'payout_date' => 'datetime:Y-m-d',
    ];

    protected static function newFactory()
    {
        return \Modules\Savings\Database\factories\SmartLockFactory::new();
    }

    public function duration(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SmartLockDuration::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getInterestToEarnAttribute()
    {
        return floatval(($this->amount * $this->interest) + $this->amount);
    }
}
