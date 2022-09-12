<?php

namespace Modules\Bills\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillTransactionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        "bill_transaction_id",
        "user_request_payload",
        "user_response_payload",
        "processor_request_payload",
        "processor_response_payload",
        "requery_payload",
    ];

    public function billTransaction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BillTransaction::class);
    }
}
