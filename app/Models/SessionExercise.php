<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionExercise extends Model
{
    use HasFactory;
    protected $with = ['exercise', 'exercise.image', 'exercise.gif'];
    protected $table = 'exercise_phase_session';
    protected $guarded = [];

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
