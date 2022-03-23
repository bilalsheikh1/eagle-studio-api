<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductCategory::query()->updateOrInsert(['name' => 'Android', "icon" => "android"], ['name' => 'Android', "icon" => "android"]);
        ProductCategory::query()->updateOrInsert(['name' => 'iOS', "icon" => "ios"], ['name' => 'iOS', "icon" => "ios"]);
        ProductCategory::query()->updateOrInsert(['name' => 'Unity', "icon" => "unity"], ['name' => 'Unity', "icon" => "unity"]);
    }
}
