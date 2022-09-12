<?php

namespace Modules\Wallet\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserCard extends Model
{
    use HasFactory;

    protected $fillable = ["user_id","first_6digits","last_4digits","issuer","country","type","token","expiry","is_default"];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    protected $hidden = [
        'token','last_4digits'
    ];

    protected static function newFactory()
    {
        return \Modules\Wallet\Database\factories\UserCardFactory::new();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setCardTokenAttribute($value)
    {
        $this->attributes['card_token'] = encrypt($value);
    }

    public function getCardTokenAttribute($value)
    {
        return decrypt($value);
    }

    public function setFirst6digitsAttribute($value)
    {
        $this->attributes['first_6digits'] = encrypt($value);
    }

    public function getFirst6digitsAttribute($value)
    {
        return decrypt($value);
    }

    public function setLast4digitsAttribute($value)
    {
        $this->attributes['last_4digits'] = encrypt($value);
    }

    public function getLast4digitsAttribute($value)
    {
        return decrypt($value);
    }
}
