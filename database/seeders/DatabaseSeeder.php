<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Database\Seeders\SmartRuleCategoryTableSeeder;
use Modules\Bills\Database\Seeders\PrimeAirtimeSeedTableSeeder;
use Modules\Bills\Database\Seeders\VtpassAirtimeSeedTableSeeder;
use Modules\Circle\Database\Seeders\PrivateCircleTableSeeder;
use Modules\Circle\Database\Seeders\PublicCircleTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
        //     UserTableSeeder::class,
        //    PublicCircleTableSeeder::class,
        //    PrivateCircleTableSeeder::class,
        //    PrimeAirtimeSeedTableSeeder::class,
        //     VtpassAirtimeSeedTableSeeder::class,
        //    SmartRuleCategoryTableSeeder::class,
        //    UserSeederTable::class,
            //PlanSeederTable::class
          //UserBank::class,
          //CustomSeederTable::class
        ]);
    }
}
