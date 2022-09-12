<?php

namespace Modules\Savings\Models;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SavingWalletTransaction extends Model
{
    use HasFactory;

    const PENDING = 'pending';
    const FAILED = 'failed';
    const SUCCESS = 'success';
    const REVERSED = 'reversed';

    protected $fillable = [
        "savings_wallet_id",
        "user_id",
        "amount",
        "transaction_ref",
        "narration",
        "status",
        "type",
    ];

    const TRANSACTION_TYPE = [
        'set-forget' => 'Set And Forget',
        'round-up' => 'Round Up',
        'pay-day' => 'PayDay',
        '52-weeks' => '52 Weeks'
    ];

    public static function createSavingsTransaction(int $walletId, int $userId, float $amount, string $transaction_ref, string $status, string $type)
    {
        return self::create([
            'savings_wallet_id' => $walletId,
            'user_id' => $userId,
            'amount' => $amount,
            'transaction_ref' => $transaction_ref,
            'status' => $status,
            'type' => $type,
        ]);
    }

    /**
    * @var mixed
    */
    public string $transaction_ref;

    public function savingsWallet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SavingsWallet::class);
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
