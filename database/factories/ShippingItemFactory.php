<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShippingItem>
 */
class ShippingItemFactory extends Factory
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
            'account_id' => Account::factory(),
            'delivery_date' => $this->faker->dateTimeBetween('+1 day', '+30 days'),
            'delivery_time' => $this->faker->time(),
            'delivery_first_name' => $this->faker->firstName(),
            'delivery_last_name' => $this->faker->lastName(),
            'delivery_street' => $this->faker->streetName(),
            'delivery_housenumber' => $this->faker->buildingNumber(),
            'delivery_zipcode' => $this->faker->postcode(),
            'delivery_city' => $this->faker->city(),
            'delivery_country_id' => 1,
            'pickup_option_id' => $this->faker->numberBetween(1, 5),
            'pickup_date' => $this->faker->dateTimeBetween('now', '+7 days'),
            'pickup_time' => $this->faker->time(),
            'pickup_first_name' => $this->faker->firstName(),
            'pickup_last_name' => $this->faker->lastName(),
            'pickup_street' => $this->faker->streetName(),
            'pickup_housenumber' => $this->faker->buildingNumber(),
            'pickup_zipcode' => $this->faker->postcode(),
            'pickup_city' => $this->faker->city(),
            'pickup_country_id' => 1,
            'return_option_id' => $this->faker->numberBetween(1, 5),
            'return_date' => $this->faker->dateTimeBetween('+31 days', '+60 days'),
            'return_time' => $this->faker->time(),
            'return_first_name' => $this->faker->firstName(),
            'return_last_name' => $this->faker->lastName(),
            'return_street' => $this->faker->streetName(),
            'return_housenumber' => $this->faker->buildingNumber(),
            'return_zipcode' => $this->faker->postcode(),
            'return_city' => $this->faker->city(),
            'return_country_id' => 1,
            'price_delivery' => $this->faker->randomFloat(2, 5, 50),
            'price_pickup' => $this->faker->randomFloat(2, 5, 50),
            'price_return' => $this->faker->randomFloat(2, 5, 50),
            'total_price' => $this->faker->randomFloat(2, 15, 150),
            'status_id' => $this->faker->numberBetween(1, 5),
            'transaction_done' => $this->faker->boolean(),
            'transaction_id' => $this->faker->optional()->uuid(),
        ];
    }
}
