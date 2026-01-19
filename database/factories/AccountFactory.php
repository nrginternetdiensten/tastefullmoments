<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'country_defaults' => [
                'currency' => fake()->currencyCode(),
                'timezone' => fake()->timezone(),
            ],
            'credit_limit_cents' => fake()->numberBetween(100000, 10000000),
            'balance_cents' => fake()->numberBetween(0, 1000000),
            'wallet_status' => fake()->randomElement(['active', 'suspended', 'closed']),
        ];
    }
}
