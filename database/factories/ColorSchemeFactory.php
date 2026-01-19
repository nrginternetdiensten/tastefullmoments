<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ColorScheme>
 */
class ColorSchemeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colors = [
            ['bg' => 'bg-blue-500', 'text' => 'text-white'],
            ['bg' => 'bg-green-500', 'text' => 'text-white'],
            ['bg' => 'bg-red-500', 'text' => 'text-white'],
            ['bg' => 'bg-yellow-500', 'text' => 'text-gray-900'],
            ['bg' => 'bg-purple-500', 'text' => 'text-white'],
            ['bg' => 'bg-pink-500', 'text' => 'text-white'],
            ['bg' => 'bg-indigo-500', 'text' => 'text-white'],
            ['bg' => 'bg-gray-500', 'text' => 'text-white'],
        ];

        $color = fake()->randomElement($colors);

        return [
            'name' => fake()->words(2, true),
            'bg_class' => $color['bg'],
            'text_class' => $color['text'],
            'active' => fake()->boolean(90),
            'list_order' => 10,
        ];
    }
}
