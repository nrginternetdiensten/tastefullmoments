<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faq>
 */
class FaqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'faq_category_id' => \App\Models\FaqCategory::factory(),
            'question' => fake()->sentence().'?',
            'answer' => fake()->paragraph(3),
            'list_order' => fake()->numberBetween(1, 100),
            'active' => true,
        ];
    }
}
