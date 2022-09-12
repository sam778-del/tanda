<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMobileCredential extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setPublicKeyAttribute($public_key)
    {
        $this->attributes['public_key'] = is_null($public_key) ? null : encrypt($public_key);
    }

    public function getPublicKeyAttribute($value)
    {
        return is_null($value) ? null : decrypt($value);
    }

    public function setDeviceIdAttribute($deviceId)
    {
        $this->attributes['device_id'] = encrypt($deviceId);
    }

    public function getDeviceIdAttribute($deviceId)
    {
        return decrypt($deviceId);
    }
}
