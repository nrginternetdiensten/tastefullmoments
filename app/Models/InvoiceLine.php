<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceLine extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceLineFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'name',
        'quantity',
        'price',
        'price_tax',
        'price_exc',
        'total',
        'total_exc',
        'total_tax',
        'tax_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'price' => 'decimal:2',
            'price_tax' => 'decimal:2',
            'price_exc' => 'decimal:2',
            'total' => 'decimal:2',
            'total_exc' => 'decimal:2',
            'total_tax' => 'decimal:2',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(InvoiceTax::class, 'tax_id');
    }
}
