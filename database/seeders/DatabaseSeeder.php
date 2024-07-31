<?php

namespace Database\Seeders;

use App\Models\Idea;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Ahmed Wassim',
            'email' => 'ahmedwassim317@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        Idea::factory(100)->create();

        $this->call([
            CategorySeeder::class,
            StatusSeeder::class,
        ]);
    }
}