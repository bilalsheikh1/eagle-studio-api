<?php

namespace Database\Seeders;

use App\Models\License;
use Illuminate\Database\Seeder;

class LicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $license = [
            ["key" => "single_app_license", "value" => "Test"],
            ["key" => "multi_app_license", "value" => "Test"],
            ["key" => "reskinned_app_license", "value" => "Test"]
        ];
        License::query()->upsert($license,["key"], ["key"]);
    }
}
