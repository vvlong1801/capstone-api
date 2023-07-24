<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Rating extends Model
{
    use HasFactory;

    protected $guarded = [];
    /**
     * Get the parent messageable model (post or video).
     */
    public function rateable(): MorphTo
    {
        return $this->morphTo();
    }

    public function rateBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
