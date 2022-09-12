<?php

namespace Modules\Circle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CircleMember extends Model
{
    use HasFactory;

    const PENDING = 'Pending';
    const ACCEPTED = 'Accepted';
    const INACTIVE = 'In-Active';

    const PAYMENT_METHOD = [
        'auto_contribute',
        'employer_pay',
        'manual_pay'
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'payout_date' => 'datetime:Y-m-d',
        'is_reserved' => 'boolean',
        'has_collected' => 'boolean',
    ];

    protected static function newFactory(): \Modules\Circle\Database\factories\CircleMemberFactory
    {
        return \Modules\Circle\Database\factories\CircleMemberFactory::new();
    }

    public function circle(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Circle::class);
    }
}
