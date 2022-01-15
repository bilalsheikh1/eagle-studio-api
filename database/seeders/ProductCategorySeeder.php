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
        ProductCategory::query()->updateOrInsert(['name' => 'Android'], ['name' => 'Android']);
        ProductCategory::query()->updateOrInsert(['name' => 'iOS'], ['name' => 'iOS']);
        ProductCategory::query()->updateOrInsert(['name' => 'Unity'], ['name' => 'Unity']);
    }
}
