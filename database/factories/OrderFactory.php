<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'table_id' => $this->faker->numberBetween(1, 10), // Случайный ID стола от 1 до 10
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed', 'canceled']), // Случайный статус
        ];
    }
}
