<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorScheme extends Model
{
    /** @use HasFactory<\Database\Factories\ColorSchemeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'bg_class',
        'text_class',
        'active',
        'list_order',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'list_order' => 'integer',
        ];
    }
}
