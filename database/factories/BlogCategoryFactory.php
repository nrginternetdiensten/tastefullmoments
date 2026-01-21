<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogCategory>
 */
class BlogCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(rand(2, 4), true);

        return [
            'name' => ucfirst($name),
            'seo_title' => fake()->sentence(),
            'seo_keywords' => implode(', ', fake()->words(10)),
            'seo_description' => fake()->paragraph(),
            'seo_url' => fake()->unique()->slug(),
            'content' => fake()->paragraphs(5, true),
            'list_order' => fake()->numberBetween(0, 100),
            'active' => fake()->boolean(80),
        ];
    }
}
