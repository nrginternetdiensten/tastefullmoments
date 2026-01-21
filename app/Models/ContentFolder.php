<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentFolder extends Model
{
    /** @use HasFactory<\Database\Factories\ContentFolderFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'color_scheme_id',
        'list_order',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'list_order' => 'integer',
        ];
    }

    /**
     * Get the color scheme for the content folder.
     */
    public function colorScheme(): BelongsTo
    {
        return $this->belongsTo(ColorScheme::class);
    }
}
