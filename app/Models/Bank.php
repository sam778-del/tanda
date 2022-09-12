<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Bank extends Model
{
    use SoftDeletes;

    public $fillable = [
        'bank_name',
        'bank_code',
        'bank_nibss_code'
    ];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'id' => 'integer',
        'bank_name' => 'string',
        'bank_code' => 'integer',
        'bank_nibss_code' => 'integer'
    ];




}
