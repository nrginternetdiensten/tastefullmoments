<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketStatus>
 */
class TicketStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'active' => fake()->boolean(80),
            'class' => fake()->randomElement(['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500', 'bg-gray-500']),
            'list_order' => fake()->numberBetween(0, 100),
        ];
    }
}
