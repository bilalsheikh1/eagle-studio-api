<?php

namespace Database\Seeders;

use App\Models\EmailDesign;
use Illuminate\Database\Seeder;

class EmailDesignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emailDesign = [
            ["key" => "send_password_to_email", "value" => "test"]
        ];
        EmailDesign::query()->upsert($emailDesign, ["key"], ["key"]);
    }
}
