<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Savings\Models\SmartLock;

class SmartLockDuration extends Model
{
    use HasFactory;

    const STATUS = ['Active', 'In-Active'];

    protected $fillable = ["name","percentage","status"];

    protected static function newFactory()
    {
        return \Modules\Admin\Database\factories\SmartLockDurationFactory::new();
    }

    public function smartLock(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SmartLock::class);
    }
}
