<?php

namespace Modules\Circle\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Circle\Models\Circle;

class CircleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            PrivateCircleTableSeeder::class,
            PublicCircleTableSeeder::class
        ]);
    }
}
