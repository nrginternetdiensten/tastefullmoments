<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvoiceStatus extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceStatusFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'active',
        'color_scheme_id',
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

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'status_id');
    }
}
