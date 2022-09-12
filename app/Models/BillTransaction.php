<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillTransaction extends Model
{
    protected $table = 'bill_transactions';

    /**
     * Get the user associated with the BillTransaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get the bill associated with the BillTransaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function billPayment()
    {
        return $this->hasOne(BillPayment::class, 'id', 'bill_id');
    }
}
