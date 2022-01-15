<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SystemSetting::query()->updateOrInsert(['label' => 'Name', 'key' => 'name', 'value' => 'bilal', 'type' => "text", "show_home" => "1", "required"=> "1", "position"=> "1"],['label' => 'Name', 'key' => 'name', 'value' => 'bilal', 'type' => "text", "show_home" => "1", "required"=> "1", "position"=> "1"]);
        SystemSetting::query()->updateOrInsert(['label' => 'Slider', 'key' => 'slider', 'value' => '0', 'type' => "switch", "show_home" => "1", "required"=> "0", "position"=> "2"],['label' => 'Slider', 'key' => 'slider', 'value' => '0', 'type' => "switch", "show_home" => "1", "required"=> "0", "position"=> "2"]);
        SystemSetting::query()->updateOrInsert(['label' => 'Paypal Client ID', 'key' => 'paypal_client_id', 'value' => '', 'type' => "text", "show_home" => "1", "required"=> "1", "position"=> "3"],['label' => 'Paypal Client ID', 'key' => 'paypal_client_id', 'value' => '', 'type' => "text", "show_home" => "1", "required"=> "1", "position"=> "3"]);
    }
}
