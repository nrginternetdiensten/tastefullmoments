<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'total',
        'total_tax',
        'total_exc',
        'status_id',
        'account_id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'total_tax' => 'decimal:2',
            'total_exc' => 'decimal:2',
        ];
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(InvoiceStatus::class, 'status_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class);
    }
}
