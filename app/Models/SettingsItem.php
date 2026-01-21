<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingsItem extends Model
{
    /** @use HasFactory<\Database\Factories\SettingsItemFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'value',
        'fieldtype_id',
        'category_id',
        'list_order',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function fieldType()
    {
        return $this->belongsTo(SettingsFieldType::class, 'fieldtype_id');
    }

    public function category()
    {
        return $this->belongsTo(SettingsCategory::class, 'category_id');
    }
}
