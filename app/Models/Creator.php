<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\MediaCollection;
use App\Enums\StatusCreator;
use App\Enums\TypeTag;
use App\Enums\TypeWorkCreator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Creator extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'gender' => Gender::class,
        'work_type' => TypeWorkCreator::class,
        'status' => StatusCreator::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function certificateIssuer()
    {
        return $this->belongsTo(CertificateIssuer::class);
    }

    public function certificate()
    {
        return $this->morphOne(Media::class, 'mediable')->whereImage()->where('collection_name', MediaCollection::PersonalTrainerCertificate);
    }

    public function workoutTrainingMedia()
    {
        return $this->morphMany(Media::class, 'mediable')->whereImage()->where('collection_name', MediaCollection::TrainingWorkout);
    }

    public function techniques()
    {
        return $this->belongsToMany(Tag::class, 'technique_tag')->whereType(TypeTag::CreatorTechnique);
    }

    public function challenges(): Attribute
    {
        return Attribute::make(
            get: fn () =>
            $this->user->challenges()->count()
        );
    }

    public function exercises(): Attribute
    {
        return Attribute::make(
            get: fn () =>
            $this->user->exercises()->count()
        );
    }

    public function members(): Attribute
    {
        return Attribute::make(get: function () {
            $challengeIds = Challenge::where('created_by', $this->user->id)->pluck('id');
            $members = DB::table('challenge_members')->select('user_id')->whereIn('challenge_id', $challengeIds)->distinct()->get()->count();
            return $members;
        });
    }

    public function isPT(): Attribute
    {
        return Attribute::make(get: function () {
            return $this->verified_at != null;
        });
    }
}
