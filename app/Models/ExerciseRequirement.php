<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseRequirement extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'value' => 'double'
    ];
}
