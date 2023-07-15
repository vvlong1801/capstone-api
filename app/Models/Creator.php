<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\MediaCollection;
use App\Enums\TypeTag;
use App\Enums\TypeWorkCreator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creator extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'gender' => Gender::class,
        'work_type' => TypeWorkCreator::class,
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
}
