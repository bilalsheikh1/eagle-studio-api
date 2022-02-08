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
        User::query()->upsert([
            ['name' => 'bilal', "email" => "bilal@gmail.com", "username" => "bilal", "password" => Hash::make('bilal'),"is_admin" => 1 ,"active" => "1"]
        ],['email', 'username'],['email', 'username']);
    }
}
