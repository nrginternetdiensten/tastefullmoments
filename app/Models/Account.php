<?php

namespace App\Models;

use App\WalletStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Account extends Model
{
    /** @use HasFactory<\Database\Factories\AccountFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
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
}
