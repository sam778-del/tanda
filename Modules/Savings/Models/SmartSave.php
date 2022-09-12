<?php

namespace Modules\Savings\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmartSave extends Model
{
    use HasFactory;

    const STATUS = ['Active', 'Completed'];
    const PAYMENT_MODE = ['Wallet', 'Card'];

    protected $fillable = ["user_id","amount","name","payback_date","payment_method"];

    protected static function newFactory()
    {
        return \Modules\Savings\Database\factories\SmartSaveFactory::new();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
