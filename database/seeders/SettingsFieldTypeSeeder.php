<?php

namespace Database\Seeders;

use App\Models\SettingsFieldType;
use Illuminate\Database\Seeder;

class SettingsFieldTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fieldTypes = [
            ['name' => 'Textfield', 'list_order' => 1, 'active' => true],
            ['name' => 'Textarea', 'list_order' => 2, 'active' => true],
            ['name' => 'Yes/No', 'list_order' => 3, 'active' => true],
        ];

        foreach ($fieldTypes as $fieldType) {
            SettingsFieldType::firstOrCreate(
                ['name' => $fieldType['name']],
                $fieldType
            );
        }

        $this->command->info('Created field types: Textfield, Textarea, Yes/No.');
    }
}
