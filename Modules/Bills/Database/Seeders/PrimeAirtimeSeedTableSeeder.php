<?php

namespace Modules\Bills\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Bills\Models\Bill;

class PrimeAirtimeSeedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        $this->populateAirtime();
    }

    private function populateAirtime()
    {
        $payload = [
            [
                "name" => "MTN Nigeria",
                "vendor" => Bill::PRIME_REFILL_SERVICE,
                "category" => Bill::AIRTIME,
                "group_name" => "MTN",
                "service_code" => null,
                "image" => null,
                "amount" => 0.00000,
                "fee" => 0.00000,
                "description" => null,
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "GLO Nigeria",
                "vendor" => Bill::PRIME_REFILL_SERVICE,
                "category" => Bill::AIRTIME,
                "group_name" => "GLO",
                "service_code" => null,
                "image" => null,
                "amount" => 0.00000,
                "fee" => 0.00000,
                "description" => null,
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "9Mobile",
                "vendor" => Bill::PRIME_REFILL_SERVICE,
                "category" => Bill::AIRTIME,
                "group_name" => "9Mobile",
                "service_code" => null,
                "image" => null,
                "amount" => 0.00000,
                "fee" => 0.00000,
                "description" => null,
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "Airtel Nigeria",
                "vendor" => Bill::PRIME_REFILL_SERVICE,
                "category" => Bill::AIRTIME,
                "group_name" => "Airtel",
                "service_code" => null,
                "image" => null,
                "amount" => 0.00000,
                "fee" => 0.00000,
                "description" => null,
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
            ],
        ];
        return Bill::insert($payload);
    }
}
