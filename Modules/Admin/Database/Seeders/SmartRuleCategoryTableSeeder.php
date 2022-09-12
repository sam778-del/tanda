<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\SmartRuleCategory;

class SmartRuleCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (SmartRuleCategory::query()->count() <= 0) {
            $categories = json_decode(file_get_contents(public_path("smart_lock_category.json")), true);
            foreach ($categories as $name => $description) {
                SmartRuleCategory::query()->create([
                    'name' => $name,
                    'description' => $description,
                    'status' => 'Active'
                ]);
            }
        }
    }
}
