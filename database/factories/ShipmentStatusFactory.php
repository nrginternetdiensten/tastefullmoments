<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShipmentStatus>
 */
class ShipmentStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['In behandeling', 'Opgepakt', 'Onderweg', 'Afgeleverd', 'Geretourneerd']),
            'description' => $this->faker->sentence(),
            'list_order' => $this->faker->numberBetween(1, 100),
            'active' => true,
            'color_scheme_id' => null,
        ];
    }
}
