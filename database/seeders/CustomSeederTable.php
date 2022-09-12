<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert('insert into saving_wallet_transactions (id, savings_wallet_id, user_id, amount, transaction_ref, narration, status) values (?, ?, ?, ?, ?, ?, ?)', [1, 1, 1, 1000, 'xsdfg', 'hello', 'pending']);
    }
}
