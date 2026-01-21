<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccountType>
 */
class AccountTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Basic', 'Professional', 'Enterprise', 'Premium']),
            'price_month' => fake()->randomFloat(2, 10, 200),
            'price_quarter' => fake()->randomFloat(2, 25, 500),
            'price_year' => fake()->randomFloat(2, 100, 2000),
            'tax_id' => \App\Models\InvoiceTax::factory(),
            'list_order' => fake()->numberBetween(1, 10),
            'active' => fake()->boolean(80),
            'color_scheme_id' => \App\Models\ColorScheme::factory(),
        ];
    }
}
