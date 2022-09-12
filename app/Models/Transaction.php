<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    const PENDING = 'pending';
    const FAILED = 'failed';
    const SUCCESS = 'success';
    const REVERSED = 'reversed';

    const TRANSACTION_TYPE = [
        'airtime' => 'Airtime Top Up',
        'data' => 'Data Top Up',
        'cable' => 'Cable Top Up',
        'round_up' => 'Round-Up',
        'pay_day' => 'Pay-Day',
        '52_weeks' => '52 Weeks Rule',
        'withdrawal' => 'Fund Withdrawal',
        'deposit' => 'Fund Deposit',
        'reversal' => 'Fund Reversal'
    ];

    protected $fillable = [
        "user_id",
        "amount",
        "status",
        "description",
        "transaction_type",
        "transaction_id",
        "transaction_ref",
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public static function createTransaction(User $user, float $amount, string $status)
    {
        self::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'status' => $status,
        ]);
    }

    public static function generateRef(): string
    {
        return time() . rand(10 * 45, 100 * 98);

    }

    public static function totalTransactionPrice()
    {
        return '₦'.number_format(Transaction::sum('amount'));
    }

    public static function todayTransactionPrice()
    {
        return '₦'.number_format(Transaction::whereDate('created_at', '=', Carbon::now())->sum('amount'));
    }

    public static function monthlyTransaction()
    {
        return '₦'.number_format(Transaction::whereDate('created_at', '=', Carbon::now()->month)->sum('amount'));
    }
}
