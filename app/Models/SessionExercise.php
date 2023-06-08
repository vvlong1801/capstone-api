<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionExercise extends Model
{
    use HasFactory;
    protected $with = ['requirements', 'exercise'];
    protected $table = 'exercise_workout_session';
    protected $guarded = [];

    public function requirements()
    {
        return $this->hasMany(ExerciseRequirement::class, 'session_exercise_id', 'id');
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
