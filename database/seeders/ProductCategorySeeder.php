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
        ProductCategory::query()->updateOrInsert(['name' => 'Android', "icon" => "SiAndroid"], ['name' => 'Android', "icon" => "SiAndroid"]);
        ProductCategory::query()->updateOrInsert(['name' => 'iOS', "icon" => "SiIos"], ['name' => 'iOS', "icon" => "SiIos"]);
        ProductCategory::query()->updateOrInsert(['name' => 'Unity', "icon" => "SiUnity"], ['name' => 'Unity', "icon" => "SiUnity"]);
    }
}
