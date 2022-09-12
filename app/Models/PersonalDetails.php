<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalDetails extends Model
{
    protected $table = 'personal_details';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'citizenship',
        'dob',
        'phone',
    ];
}
