<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\InvoiceStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalExc = fake()->randomFloat(2, 100, 10000);
        $totalTax = round($totalExc * 0.21, 2);
        $total = $totalExc + $totalTax;

        $account = Account::inRandomOrder()->first() ?? Account::factory()->create();
        $user = User::factory()->create();
        $user->accounts()->sync([$account->id]);

        return [
            'invoice_id' => 'INV-'.fake()->unique()->numberBetween(10000, 99999),
            'total' => $total,
            'total_tax' => $totalTax,
            'total_exc' => $totalExc,
            'status_id' => InvoiceStatus::inRandomOrder()->first()?->id,
            'account_id' => $account->id,
            'user_id' => $user->id,
        ];
    }
}
