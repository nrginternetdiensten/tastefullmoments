<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountTransaction extends Model
{
    /** @use HasFactory<\Database\Factories\AccountTransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'account_id',
        'type',
        'amount_cents',
        'description',
        'notes',
        'reference',
        'transaction_date',
    ];

    protected function casts(): array
    {
        return [
            'amount_cents' => 'integer',
            'transaction_date' => 'datetime',
        ];
    }

    /**
     * Get the account that owns the transaction.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Check if the transaction is a debit.
     */
    public function isDebit(): bool
    {
        return $this->type === 'debit';
    }

    /**
     * Check if the transaction is a credit.
     */
    public function isCredit(): bool
    {
        return $this->type === 'credit';
    }
}
