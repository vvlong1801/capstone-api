<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\LevelWorkoutUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'gender' => Gender::class,
        'level' => LevelWorkoutUser::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
