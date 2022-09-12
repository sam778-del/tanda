<?php

namespace Modules\Wallet\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletHistory extends Model
{
    use HasFactory;

    protected $fillable = ["user_id","previous_amount","current_amount"];

    protected static function newFactory()
    {
        return \Modules\Wallet\Database\factories\WalletHistoryFactory::new();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
