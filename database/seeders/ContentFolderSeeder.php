<?php

namespace Database\Seeders;

use App\Models\ContentFolder;
use Illuminate\Database\Seeder;

class ContentFolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = rand(5, 10);
        ContentFolder::factory()->count($count)->create();

        $this->command->info("Created {$count} content folders.");
    }
}
