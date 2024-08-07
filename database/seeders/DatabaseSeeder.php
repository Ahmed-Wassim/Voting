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

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            StatusSeeder::class,
            IdeaSeeder::class,
            CommentSeeder::class,
        ]);

        // i faced problem with user_id is null and tried many solution but it didn't work so did it with hard work;
        foreach (Idea::all() as $idea) {
            $idea->update([
                'user_id' => User::inRandomOrder()->first()->id,
            ]);
        }

        foreach (Idea::limit(100)->get() as $idea) {
            foreach (User::inRandomOrder()->limit(rand(1, 10))->get() as $user) {
                $idea->votes()->attach($user->id);
            }
        }
    }
}