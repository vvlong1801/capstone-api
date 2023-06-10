<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Enums\MediaCollection;
use App\Enums\TypeMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'collection_name' => MediaCollection::class,
        'status' => CommonStatus::class,
    ];

    public function mediable()
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include image.
     */
    public function scopeWhereImage(Builder $query): void
    {
        $query->whereIn('mime_type', ['png', 'jpg', 'jpeg']);
    }

    /**
     * Scope a query to only include image.
     */
    public function scopeWhereGif(Builder $query): void
    {
        $query->where('mime_type', 'gif');
    }

    /**
     * Scope a query to only include image.
     */
    public function scopeWhereIcon(Builder $query): void
    {
        $query->where('mime_type', 'svg');
    }

    /**
     * Scope a query to only include image.
     */
    public function scopeWhereVideo(Builder $query): void
    {
        $query->where('mime_type', ['mp3', 'mp4']);
    }
}
