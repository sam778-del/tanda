<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualAccount extends Model
{

    use HasFactory;
	protected $fillable = ["currency",
		"balance",
		"status",
		"type",
		"bank_name",
		"bank_code",
		"kyc_level",
		"account_holder",
		"account_name",
		"account_number",
		"user_id"
	];
	public function user(){
		return $this->belongsTo(User::class);
	}
}
