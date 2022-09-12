<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TandaNotification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "user_id",
        "type",
        "email",
        "phone_no",
        "subject",
        "message",
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
