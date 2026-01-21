<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingsCategory extends Model
{
    /** @use HasFactory<\Database\Factories\SettingsCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'list_order',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
