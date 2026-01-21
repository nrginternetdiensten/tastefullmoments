<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FaqCategory extends Model
{
    /** @use HasFactory<\Database\Factories\FaqCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'list_order',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }
}
