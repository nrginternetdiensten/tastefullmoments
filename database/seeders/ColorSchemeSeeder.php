<?php

namespace Database\Seeders;

use App\Models\ColorScheme;
use Illuminate\Database\Seeder;

class ColorSchemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            'red', 'orange', 'amber', 'yellow', 'lime', 'green',
            'emerald', 'teal', 'cyan', 'sky', 'blue', 'indigo',
            'violet', 'purple', 'fuchsia', 'pink', 'rose',
        ];

        $order = 10;

        foreach ($colors as $color) {
            // Lichte variant
            ColorScheme::create([
                'name' => ucfirst($color).' Licht',
                'bg_class' => "bg-{$color}-100",
                'text_class' => "text-{$color}-700",
                'active' => true,
                'list_order' => $order++,
            ]);

            // Normale variant
            ColorScheme::create([
                'name' => ucfirst($color),
                'bg_class' => "bg-{$color}-500",
                'text_class' => 'text-white',
                'active' => true,
                'list_order' => $order++,
            ]);

            // Donkere variant
            ColorScheme::create([
                'name' => ucfirst($color).' Donker',
                'bg_class' => "bg-{$color}-700",
                'text_class' => "text-{$color}-100",
                'active' => true,
                'list_order' => $order++,
            ]);
        }
    }
}
