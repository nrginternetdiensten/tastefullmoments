<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeadItem>
 */
class LeadItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'companyname' => fake()->company(),
            'streetname' => fake()->streetName(),
            'housenumber' => fake()->buildingNumber(),
            'zipcode' => fake()->postcode(),
            'city' => fake()->city(),
            'emailadres' => fake()->email(),
            'phonenumber' => fake()->phoneNumber(),
            'ipaddress' => fake()->ipv4(),
            'internal_note' => fake()->optional()->paragraph(),
            'lead_status_id' => \App\Models\LeadStatus::factory(),
            'lead_channel_id' => \App\Models\LeadChannel::factory(),
            'lead_category_id' => \App\Models\LeadCategory::factory(),
        ];
    }
}
