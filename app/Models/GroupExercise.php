<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class GroupExercise extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function image(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class);
    }
}
