<?php

namespace Database\Seeders;

use App\Models\ProductTemplate;
use Illuminate\Database\Seeder;

class ProductTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductTemplate::query()->updateOrInsert(['name' => 'Game', 'urn' => 'game-template'],
            ['name' => 'Game', 'urn' => 'game-template']);
        ProductTemplate::query()->updateOrInsert(['name' => 'App', 'urn' => 'app-template'],
            ['name' => 'App', 'urn' => 'app-template']);
        ProductTemplate::query()->updateOrInsert(['name' => 'Ready2Use', 'urn' => 'ready-use'],
            ['name' => 'Ready2Use', 'urn' => 'ready-use']);
    }
}
