<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SettingsItem>
 */
class SettingsItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->slug(2),
            'title' => fake()->words(3, true),
            'value' => fake()->sentence(),
            'fieldtype_id' => \App\Models\SettingsFieldType::factory(),
            'category_id' => \App\Models\SettingsCategory::factory(),
            'list_order' => fake()->numberBetween(0, 100),
            'active' => fake()->boolean(90),
        ];
    }
}
