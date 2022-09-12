<?php

namespace Modules\Savings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmartRule extends Model
{
    use HasFactory;

    const ASCENDING = 'Ascending';
    const DESCENDING = 'Descending';

    const RULE_TYPE = [
        [
            'name' => 'Round-Up',
            'description' =>'Round Up every transaction and set aside the spare change'
        ],
        [
            'name' => 'Payday',
            'description' =>'Save a percentage every time you get a minimum deposit of a certain amount in your bank account'
        ],
        [
            'name' => '52-Weeks-Rule',
            'description' => 'Save 100 naira week 1 and 200 week 2 and so on for a whole year'
        ],
        [
            'name' => 'Set-and-Forget',
            'description' => 'This is a default weekly savings'
        ],

    ];

    protected $fillable = ['user_id','smart_goal_id','type', 'deduction_date','amount','configuration'];

    protected $casts = [
        'deduction_date' => 'date:Y-m-d',
    ];

    public function savings_wallet_history(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SavingsWalletHistory::class);
    }

    public function smartGoal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SmartGoal::class);
    }
}
