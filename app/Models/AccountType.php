<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountType extends Model
{
    /** @use HasFactory<\Database\Factories\AccountTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'price_month',
        'price_quarter',
        'price_year',
        'tax_id',
        'list_order',
        'active',
        'color_scheme_id',
    ];

    protected function casts(): array
    {
        return [
            'price_month' => 'decimal:2',
            'price_quarter' => 'decimal:2',
            'price_year' => 'decimal:2',
            'active' => 'boolean',
        ];
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(InvoiceTax::class, 'tax_id');
    }

    public function colorScheme(): BelongsTo
    {
        return $this->belongsTo(ColorScheme::class);
    }
}
