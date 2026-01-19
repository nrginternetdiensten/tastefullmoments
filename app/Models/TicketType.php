<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketType extends Model
{
    /** @use HasFactory<\Database\Factories\TicketTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'color_scheme_id',
        'active',
        'class',
        'list_order',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'list_order' => 'integer',
        ];
    }

    public function colorScheme(): BelongsTo
    {
        return $this->belongsTo(ColorScheme::class);
    }
}
