<?php

namespace Database\Seeders;

use App\Models\ContentType;
use Illuminate\Database\Seeder;

class ContentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = rand(5, 10);
        ContentType::factory()->count($count)->create();

        $this->command->info("Created {$count} content types.");
    }
}
