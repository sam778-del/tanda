<?php

namespace Modules\Bills\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Bills\Models\Bill;

class VtpassAirtimeSeedTableSeeder extends Seeder
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
                "vendor" => Bill::VTUPASS_REFILL_SERVICE,
                "category" => Bill::AIRTIME,
                "group_name" => "MTN",
                "service_code" => 'mtn',
                "image" => "https://sandbox.vtpass.com/resources/products/200X200/MTN-Airtime.jpg",
                "amount" => 0.00000,
                "fee" => 0.00000,
                "description" => null,
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "GLO Nigeria",
                "vendor" => Bill::VTUPASS_REFILL_SERVICE,
                "category" => Bill::AIRTIME,
                "group_name" => "GLO",
                "service_code" => 'glo',
                "image" => "https://sandbox.vtpass.com/resources/products/200X200/GLO-Airtime.jpg",
                "amount" => 0.00000,
                "fee" => 0.00000,
                "description" => null,
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "9Mobile",
                "vendor" => Bill::VTUPASS_REFILL_SERVICE,
                "category" => Bill::AIRTIME,
                "group_name" => "9Mobile",
                "service_code" => 'etisalat',
                "image" => "https://sandbox.vtpass.com/resources/products/200X200/9mobile-Airtime.jpg",
                "amount" => 0.00000,
                "fee" => 0.00000,
                "description" => null,
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "Airtel Nigeria",
                "vendor" => Bill::VTUPASS_REFILL_SERVICE,
                "category" => Bill::AIRTIME,
                "group_name" => "Airtel",
                "service_code" => 'airtel',
                "image" => "https://sandbox.vtpass.com/resources/products/200X200/Airtel-Airtime.jpg",
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
