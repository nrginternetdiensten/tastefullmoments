<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContentItem>
 */
class ContentItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'seo_title' => fake()->sentence(5),
            'seo_keywords' => fake()->words(5, true),
            'seo_description' => fake()->sentence(10),
            'seo_url' => fake()->slug(),
            'folder_id' => \App\Models\ContentFolder::factory(),
            'type_id' => \App\Models\ContentType::factory(),
            'content' => fake()->paragraphs(3, true),
        ];
    }
}
