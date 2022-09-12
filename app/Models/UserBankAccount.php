<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBankAccount extends Model
{
    protected $table = 'user_bank_accounts';

    /**
     * Get the user associated with the UserBankAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
