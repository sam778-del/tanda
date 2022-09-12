<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class Admin extends Authenticatable
{
    use HasRoles;

    const ROLE = 0;
    const STATUS = 0;
    const ENABLE = 1;
    const DISABLE = 0;

    protected $guard = 'admin';

    protected $fillable = [
        'name', 'email', 'mode', 'phone', 'password', 'role_id', 'photo', 'created_at', 'updated_at', 'remember_token'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the role associated with the Admin
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'user_id');
    }
}
