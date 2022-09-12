<?php

namespace Modules\Circle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CircleMembersPayment extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Circle\Database\factories\CircleMembersPaymentFactory::new();
    }
}
