<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountTransaction;
use Illuminate\Database\Seeder;

class AccountTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = Account::all();

        if ($accounts->isEmpty()) {
            $this->command->warn('No accounts found. Please run AccountSeeder first.');

            return;
        }

        $totalTransactions = 0;

        foreach ($accounts as $account) {
            $transactionCount = rand(10, 25);

            // Mix of debit and credit transactions
            $debitCount = rand(5, intdiv($transactionCount, 2));
            $creditCount = $transactionCount - $debitCount;

            AccountTransaction::factory()
                ->debit()
                ->count($debitCount)
                ->create(['account_id' => $account->id]);

            AccountTransaction::factory()
                ->credit()
                ->count($creditCount)
                ->create(['account_id' => $account->id]);

            $totalTransactions += $transactionCount;
        }

        $this->command->info("Created {$totalTransactions} transactions across {$accounts->count()} accounts.");
    }
}
