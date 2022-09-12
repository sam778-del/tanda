<?php

namespace Modules\Wallet\Models;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBankAccountTransaction extends Model
{
    use HasFactory;

    protected $fillable = ["user_id","user_bank_account_id","transfer_id","amount","reference","status"];

    protected $casts = [
        'status' => 'boolean'
    ];

    protected static function newFactory()
    {
        return \Modules\Wallet\Database\factories\UserBankAccountTransactionFactory::new();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function userBankAccount()
    {
        return $this->belongsTo(UserBankAccount::class);
    }

    public function transaction(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Transaction::class, 'transaction');
    }
}
