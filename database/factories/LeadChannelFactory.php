<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeadChannel>
 */
class LeadChannelFactory extends Factory
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
            'color_scheme_id' => \App\Models\ColorScheme::factory(),
            'active' => fake()->boolean(80),
            'list_order' => fake()->numberBetween(1, 100),
        ];
    }
}
