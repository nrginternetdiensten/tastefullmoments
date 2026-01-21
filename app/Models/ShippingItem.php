<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingItem extends Model
{
    /** @use HasFactory<\Database\Factories\ShippingItemFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_id',
        'delivery_date',
        'delivery_time',
        'delivery_first_name',
        'delivery_last_name',
        'delivery_street',
        'delivery_housenumber',
        'delivery_zipcode',
        'delivery_city',
        'delivery_country_id',
        'pickup_option_id',
        'pickup_date',
        'pickup_time',
        'pickup_first_name',
        'pickup_last_name',
        'pickup_street',
        'pickup_housenumber',
        'pickup_zipcode',
        'pickup_city',
        'pickup_country_id',
        'return_option_id',
        'return_date',
        'return_time',
        'return_first_name',
        'return_last_name',
        'return_street',
        'return_housenumber',
        'return_zipcode',
        'return_city',
        'return_country_id',
        'price_delivery',
        'price_pickup',
        'price_return',
        'total_price',
        'status_id',
        'transaction_done',
        'transaction_id',
    ];

    protected function casts(): array
    {
        return [
            'delivery_date' => 'date',
            'pickup_date' => 'date',
            'return_date' => 'date',
            'price_delivery' => 'decimal:2',
            'price_pickup' => 'decimal:2',
            'price_return' => 'decimal:2',
            'total_price' => 'decimal:2',
            'transaction_done' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ShipmentStatus::class, 'status_id');
    }

    public function deliveryCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'delivery_country_id');
    }

    public function pickupCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'pickup_country_id');
    }

    public function returnCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'return_country_id');
    }
}
