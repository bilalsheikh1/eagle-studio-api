<?php

namespace Database\Seeders;

use App\Models\ProductTemplate;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(FrameworkSeeder::class);
        $this->call(ProductTemplateSeeder::class);
        $this->call(SystemSettingSeeder::class);
    }
}
