<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysicalCard extends Model
{
    use HasFactory;

    protected $fillable =[
        "disposable",
        "status",
        "type",
        "currency",
        "brand",
        "card_number",
        "card_pan",
        "cvv",
        "expiry_month",
        "expiry_year",
        "last_four",
        "name_on_card",
        "balance",
        "user_id",
    ];

	public function user(){
		return $this->belongsTo(User::class);
	}
}
