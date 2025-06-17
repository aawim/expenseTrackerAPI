<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
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
            'category_id' => Category::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'description' => $this->faker->sentence,
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'location' => $this->faker->city,
            'is_recurring' => $this->faker->boolean,
        ];
    }
}
