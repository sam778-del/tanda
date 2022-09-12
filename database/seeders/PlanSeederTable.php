<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $amount = [0, 500, null];
        foreach($amount as $plan)
        {
            if($plan == 0)
            {
                Plan::create([
                    'name' => 'Free',
                    'period' => 'monthly',
                    'price' => 0
                ]);
            }elseif($plan == 500) {
                Plan::create([
                    'name' => 'Advanced',
                    'period' => 'monthly',
                    'price' => 500
                ]);
            }else{
                Plan::create([
                    'name' => 'Enterprise',
                    'period' => 'monthly',
                    'price' => null
                ]);
            }
        }
    }
}
