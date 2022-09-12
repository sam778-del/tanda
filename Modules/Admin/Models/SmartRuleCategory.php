<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmartRuleCategory extends Model
{
    use HasFactory;

    protected $table = 'smart_rules_categories';

    protected $fillable = ['name','description'];

    protected static function newFactory()
    {
        return \Modules\Admin\Database\factories\SmartRuleCategoryFactory::new();
    }
}

