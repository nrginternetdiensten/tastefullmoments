<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailItem extends Model
{
    /** @use HasFactory<\Database\Factories\EmailItemFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'content',
        'folder_id',
    ];

    public function folder(): BelongsTo
    {
        return $this->belongsTo(EmailFolder::class, 'folder_id');
    }
}
