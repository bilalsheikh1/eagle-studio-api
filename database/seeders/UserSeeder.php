<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->insert([
            'name' => 'bilal',
            "email" => "bilal@gmail.com",
            "username" => "bilal",
            "password" => Hash::make('bilal'),
            "active" => "1"
        ]);
    }
}
