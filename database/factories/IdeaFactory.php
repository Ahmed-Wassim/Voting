<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Idea>
 */
class IdeaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 100),
            'category_id' => fake()->numberBetween(1, 10),
            'status_id' => fake()->numberBetween(1, 5),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'spams' => fake()->numberBetween(0, 4),
        ];
    }
}
