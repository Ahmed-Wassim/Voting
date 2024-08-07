<?php

namespace Database\Seeders;

use App\Models\Idea;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Idea::all() as $idea) {
            for ($i = 0; $i < rand(1, 8); $i++) {
                Comment::factory()->create([
                    'idea_id' => $idea->id,
                ]);
            }
        }
    }
}