<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Employer extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use Notifiable;

    const ROLE = 0;
    const STATUS = 0;
    const ENABLE = 1;
    const DISABLE = 0;

    protected $guard = 'admin';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_no',
        'password',
        'role_id',
        'know_us',
        'photo',
        'created_at',
        'updated_at',
        'bank_name',
        'account_name',
        'account_number',
        'plan_id',
        'google_id',
        'status',
        'plan_expiry_date',
        'plan_expiry_status',
        'remember_token'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**--+
     *
     * Get the role associated with the Admin
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'user_id');
    }

    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }

    public function  employees()
    {
        return $this->hasMany(User::class, 'id', 'employer_id');
    }

    public function setPasswordAttribute($value)
    {
        if (Hash::needsRehash($value)) {
            $value = Hash::make($value);
        }
        $this->attributes['password'] = $value;
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
