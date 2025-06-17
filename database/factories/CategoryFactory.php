<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
            return [
            'user_id' => User::factory(),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'is_system_category' => $this->faker->boolean(20), // 20% chance of being system category
        ];
    }
}
