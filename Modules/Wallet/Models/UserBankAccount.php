<?php

namespace Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBankAccount extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','account_number','account_name','bank_code'];

    protected static function newFactory()
    {
        return \Modules\Wallet\Database\factories\BankFactory::new();
    }
}
