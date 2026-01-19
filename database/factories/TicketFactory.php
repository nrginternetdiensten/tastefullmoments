<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\TicketChannel;
use App\Models\TicketStatus;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $account = Account::inRandomOrder()->first() ?? Account::factory()->create();
        $user = User::factory()->create();
        $user->accounts()->sync([$account->id]);

        return [
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'account_id' => $account->id,
            'user_id' => $user->id,
            'channel_id' => TicketChannel::inRandomOrder()->first()?->id,
            'status_id' => TicketStatus::inRandomOrder()->first()?->id,
            'type_id' => TicketType::inRandomOrder()->first()?->id,
        ];
    }
}
