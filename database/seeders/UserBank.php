<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserBankAccount;

class UserBank extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserBankAccount::create([
            'user_id' => 5,
            'account_number' => 2266083995,
            'account_name' => 'Adeshina Ayomide',
            'bank_code' => '5063'
        ]);
    }
}
