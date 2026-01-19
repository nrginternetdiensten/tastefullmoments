<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketAttachment>
 */
class TicketAttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $originalFilename = fake()->word().'.'.fake()->fileExtension();

        return [
            'ticket_id' => Ticket::factory(),
            'user_id' => User::factory(),
            'filename' => fake()->uuid().'.pdf',
            'original_filename' => $originalFilename,
            'mime_type' => fake()->mimeType(),
            'size' => fake()->numberBetween(1024, 5242880),
            'path' => 'attachments/'.fake()->uuid(),
        ];
    }
}
