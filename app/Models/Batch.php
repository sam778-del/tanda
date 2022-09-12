<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_no',
        'email',
        'job_type',
        'department',
        'employment_type',
        'basic_pay',
        'housing_allowance',
        'transport_allowance'
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
