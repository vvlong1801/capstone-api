<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Enums\MediaCollection;
use App\Enums\TypeMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'type' => TypeMedia::class,
        'collection_name' => MediaCollection::class,
        'status' => CommonStatus::class,
    ];

    public function mediable()
    {
        return $this->morphTo();
    }
}
