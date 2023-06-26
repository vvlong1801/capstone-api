<?php

namespace App\Models;

use App\Enums\Level;
use App\Enums\RankWorkoutUser;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengePhase extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function level(): Attribute
    {
        return Attribute::make(
            set: fn ($name) => Level::fromName(ucfirst($name)),
            get: fn ($value) => Level::fromValue($value),
        );
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function sessions()
    {
        return $this->hasMany(PhaseSession::class);
    }
}
