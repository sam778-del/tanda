<?php

namespace App\Models;

use Database\Factories\UserStackerFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserStaker extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory(): UserStackerFactory
    {
        return UserStackerFactory::new();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stacker(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'stacker_id');
    }
}
