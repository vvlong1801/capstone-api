<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Enums\RoleMemberChallenge;
use App\Enums\StatusChallenge;
use App\Enums\TypeChallenge;
use App\Enums\TypeParticipant;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Challenge extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $casts = [
        'status' => StatusChallenge::class,
        'type' => TypeChallenge::class,
        // 'released_at' => 'datetime',
        // 'finished_at' => 'datetime',
        // 'stopped_at' => 'datetime',
    ];

    protected function releasedAt(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $value),
            get: fn ($value) => \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s'),
        );
    }
    // protected function finishedAt(): Attribute
    // {
    //     return Attribute::make(
    //         set: fn ($value) => \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $value),
    //     );
    // }
    // protected function stoppedAt(): Attribute
    // {
    //     return Attribute::make(
    //         set: fn ($value) => \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $value),
    //     );
    // }

    protected function status(): Attribute
    {
        return Attribute::make(
            // set: fn ($name) => CommonStatus::fromName(ucfirst($name)),
            get: fn ($value) => CommonStatus::fromValue($value),
        );
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            set: fn ($name) => TypeChallenge::fromName(ucfirst($name)),
            get: fn ($value) => TypeChallenge::fromValue($value),
        );
    }

    protected function participant(): Attribute
    {
        return Attribute::make(
            set: fn ($name) => TypeParticipant::fromName(ucfirst($name)),
            get: fn ($value) => TypeParticipant::fromValue($value),
        );
    }

    protected function memberCensorship(): Attribute
    {
        return Attribute::make(
            set: fn ($name) => RoleMemberChallenge::fromName(ucfirst($name)),
            get: fn ($value) => RoleMemberChallenge::fromValue($value),
        );
    }

    protected function resultCensorship(): Attribute
    {
        return Attribute::make(
            set: fn ($name) => RoleMemberChallenge::fromName(ucfirst($name)),
            get: fn ($value) => RoleMemberChallenge::fromValue(ucfirst($value)),
        );
    }

    // =============== relationship =================
    // ==============================================
    public function phases()
    {
        return $this->hasMany(ChallengePhase::class);
    }

    public function image()
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
