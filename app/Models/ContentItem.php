<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentItem extends Model
{
    /** @use HasFactory<\Database\Factories\ContentItemFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'seo_title',
        'seo_keywords',
        'seo_description',
        'seo_url',
        'folder_id',
        'type_id',
        'content',
    ];

    /**
     * Get the folder for the content item.
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(ContentFolder::class, 'folder_id');
    }

    /**
     * Get the type for the content item.
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ContentType::class, 'type_id');
    }
}
