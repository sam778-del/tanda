<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    const ENABLE = "ENABLE";
    const DISABLE = "DISABLE";
    const TERMINATED = "TERMINATED";

    protected $table = 'users';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'job_type',
        'department',
        'employment_type',
        'basic_pay',
        'housing_allowance',
        'transport_allowance',
        'employer_id',
        'phone_no',
        'app_code'
    ];
}
