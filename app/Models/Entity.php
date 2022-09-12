<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    protected $table = 'entities';

    protected $fillable = [
        'entity_name',
        'entity_type',
        'reg_number',
        'vat_id',
        'street',
        'city',
        'state',
        'user_id'
    ];
}
