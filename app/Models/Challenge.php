<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Enums\Gender;
use App\Enums\Level;
use App\Enums\RoleChallenge;
use App\Enums\RoleMemberChallenge;
use App\Enums\StatusChallenge;
use App\Enums\TypeChallenge;
use App\Enums\TypeParticipant;
use App\Enums\TypeTag;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Challenge extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $guarded = [];
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $casts = [
        'status' => StatusChallenge::class,
        'type' => TypeChallenge::class,
        'level' => Level::class,
        'for_gender' => Gender::class,
        'start_at' => 'datetime',
        'finish_at' => 'datetime',
        // 'paused_at' => 'datetime',
    ];


    protected function type(): Attribute
    {
        return Attribute::make(
            set: fn ($name) => TypeChallenge::fromName(ucfirst($name)),
            get: fn ($value) => TypeChallenge::fromValue($value),
        );
    }

    protected function numRate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return $this->ratings()->count();
            },
        );
    }

    protected function rateValue(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $rate = 0.0;
                if ($this->numRate) {
                    $rate =  $this->ratings()->sum('value') / $this->numRate;
                }
                return floatval(number_format($rate, 1));
            },
        );
    }

    // =============== relationship =================
    // ==============================================
    public function phases()
    {
        return $this->hasMany(ChallengePhase::class);
    }

    public function mainImage()
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function feedbacks(): MorphMany
    {
        return $this->morphMany(Message::class, 'messageable')->where('group', 0);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Message::class, 'messageable')->where('group', 1);
    }

    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'challenge_tag')->whereType(TypeTag::ChallengeTag);
    }

    public function invitations()
    {
        return $this->hasMany(ChallengeInvitation::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'challenge_members')->with('workoutUser')
            ->withPivot(['role', 'status', 'id'])
            ->withPivotValue('role', RoleChallenge::member->value)
            ->withTimestamps();
    }
}
