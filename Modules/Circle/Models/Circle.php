<?php

namespace Modules\Circle\Models;

use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Circle extends Model
{
    use HasFactory;

    const PUBLIC = 'Public';
    const PRIVATE = 'Private';
    const PENDING = 'Pending';

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
    ];

    protected static function newFactory(): \Modules\Circle\Database\factories\CircleFactory
    {
        return \Modules\Circle\Database\factories\CircleFactory::new();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Circle $circle) {
            $circle->code = Str::random(10);
        });
    }

    public function circleMembers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CircleMember::class);
    }
}
