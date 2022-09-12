<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaySchedule extends Model
{
    protected $table = 'pay_schedules';

    protected $fillable = [
        'user_id',
        'schedule',
        'schedule_day'
    ];
}
