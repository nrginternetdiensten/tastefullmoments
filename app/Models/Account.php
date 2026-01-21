<?php

namespace App\Models;

use App\WalletStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    /** @use HasFactory<\Database\Factories\AccountFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'company_name',
        'street_name',
        'house_number',
        'zipcode',
        'city',
        'email_address',
        'telephone_number',
        'kvk',
        'btw',
        'country_defaults',
        'credit_limit_cents',
        'balance_cents',
        'wallet_status',
    ];

    protected function casts(): array
    {
        return [
            'country_defaults' => 'array',
            'credit_limit_cents' => 'integer',
            'balance_cents' => 'integer',
            'wallet_status' => WalletStatus::class,
        ];
    }

    /**
     * Get the users for the account.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Get the transactions for the account.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(AccountTransaction::class);
    }

    /**
     * Get the tickets for the account.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
