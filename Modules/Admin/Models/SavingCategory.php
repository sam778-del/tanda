<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SavingCategory extends Model
{
    use HasFactory;

    protected $fillable = ["name","slug","image","status"];
    
    protected static function newFactory()
    {
        return \Modules\Admin\Database\factories\SavingCategoryFactory::new();
    }
}
