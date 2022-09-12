<?php

namespace Modules\Savings\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmartVest extends Model
{
    use HasFactory;

    const STATUS = ['Active', 'Completed'];
    const PAYMENT_MODE = ['Wallet', 'Card'];

    protected $fillable = ["user_id","amount","duration","initial_payment","payment_method"];

    protected static function newFactory()
    {
        return \Modules\Savings\Database\factories\SmartVestFactory::new();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
