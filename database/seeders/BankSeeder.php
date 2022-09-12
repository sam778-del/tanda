<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Bank::query()->count() <= 0) {
            $banks = json_decode(file_get_contents(public_path("banks.json")), true);
            foreach ($banks as $bankCode => $bankName) {
                Bank::query()->create([
                    'bank_name' => $bankName,
                    'bank_code' => $bankCode
                ]);
            }
        }
    }
}
