<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => \App\Models\Order::factory(),
            'menu_id' => $this->faker->numberBetween(1, 10),
            'count' => $this->faker->numberBetween(1, 5),
            'price' => $this->faker->randomFloat(2, 1, 50),
            'status' => $this->faker->randomElement(['pending', 'completed', 'canceled']),
        ];
    }
}
