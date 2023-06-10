<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muscle extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class);
    }

    public function image()
    {
        return $this->morphOne(Media::class, 'mediable')->whereImage();
    }

    public function icon()
    {
        return $this->morphOne(Media::class, 'mediable')->whereIcon();
    }
}
