<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadChannel extends Model
{
    /** @use HasFactory<\Database\Factories\LeadChannelFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'color_scheme_id',
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

    public function colorScheme()
    {
        return $this->belongsTo(ColorScheme::class);
    }
}
