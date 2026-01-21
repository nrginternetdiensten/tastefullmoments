<?php

namespace Database\Seeders;

use App\Models\LeadItem;
use Illuminate\Database\Seeder;

class LeadItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeadItem::factory()->count(100)->create();

        $this->command->info('Created 100 lead items.');
    }
}
