<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::firstOrCreate([
            'name' => 'Ahmed Wassim',
            'email' => 'ahmedwassim317@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        User::firstOrCreate([
            'name' => 'Ahmed',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
