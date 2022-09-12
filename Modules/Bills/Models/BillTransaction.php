<?php

namespace Modules\Bills\Models;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillTransaction extends Model
{
    use HasFactory;

    const PENDING = 'pending';
    const FAILED = 'failed';
    const SUCCESS = 'success';
    const REVERSED = 'reversed';

    protected $fillable = [
        "bill_id",
        "user_id",
        "amount",
        "payment_method",
        "transaction_ref",
        "narration",
        "status",
        "type",
    ];

    const TRANSACTION_TYPE = [
        'airtime' => 'Airtime',
        'data' => 'Mobile Data',
        'cable' => 'Cable',
        'electricity' => 'Electricity',
    ];

    public static function createBillTransaction(int $billId, int $userId, float $amount, string $transaction_ref, string $status, string $type)
    {
        return self::create([
            'bill_id' => $billId,
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

    public function bill(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Transaction::class, "transaction");
    }

    public function billTransactionLog(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BillTransactionLog::class);
    }
}
