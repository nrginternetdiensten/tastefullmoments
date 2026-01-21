<?php

namespace Database\Seeders;

use App\Models\ContentItem;
use Illuminate\Database\Seeder;

class ContentItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = rand(15, 30);
        ContentItem::factory()->count($count)->create();

        $this->command->info("Created {$count} content items.");
    }
}
