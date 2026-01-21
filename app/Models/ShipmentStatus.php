<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShipmentStatus extends Model
{
    /** @use HasFactory<\Database\Factories\ShipmentStatusFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'list_order',
        'active',
        'color_scheme_id',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function colorScheme(): BelongsTo
    {
        return $this->belongsTo(ColorScheme::class);
    }

    public function shippingItems(): HasMany
    {
        return $this->hasMany(ShippingItem::class, 'status_id');
    }
}
