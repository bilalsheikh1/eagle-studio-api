<?php

namespace Database\Seeders;

use App\Models\SellYourApp;
use Illuminate\Database\Seeder;

class SellYourAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SellYourApp::query()->upsert(["data" => "<p>ok</p>"], ["data"],["data"]);
    }
}
