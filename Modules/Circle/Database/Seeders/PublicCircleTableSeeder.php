<?php


namespace Modules\Circle\Database\Seeders;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Circle\Models\Circle;

class PublicCircleTableSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();
        Circle::factory()->count(5)->publicCircle()->withCircleMembers()->create();
    }

}
