<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailFolder extends Model
{
    /** @use HasFactory<\Database\Factories\EmailFolderFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color_scheme_id',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(EmailItem::class, 'folder_id');
    }

    public function colorScheme(): BelongsTo
    {
        return $this->belongsTo(ColorScheme::class);
    }
}
