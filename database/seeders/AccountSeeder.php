<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Creating a test user first.');
            $users = collect([User::factory()->create()]);
        }

        // Create 5-10 accounts
        $accountCount = rand(5, 10);
        $accounts = Account::factory()->count($accountCount)->create();

        // Attach users to accounts
        foreach ($accounts as $account) {
            $account->users()->attach(
                $users->random(rand(1, min(3, $users->count())))->pluck('id')
            );
        }

        $this->command->info("Created {$accountCount} accounts with user associations.");
    }
}
