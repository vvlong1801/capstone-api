<?php

namespace App\Models;

use App\Enums\Level;
use App\Enums\RequirementUnit;
use App\Enums\Role;
use App\Enums\TypeMedia;
use App\Enums\TypeTag;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $guarded = [];
    //=========== convert attribute ============
    //==========================================
    protected $casts = [
        "requirement_unit" => RequirementUnit::class,
    ];
    public function level(): Attribute
    {
        return Attribute::make(
            set: fn ($name) => Level::fromName($name),
            get: fn ($value) => Level::fromValue($value)
        );
    }
    //=========== create relationship ==========
    //==========================================

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function image()
    {
        return $this->morphOne(Media::class, 'mediable')->whereImage();
    }

    public function gif()
    {
        return $this->morphOne(Media::class, 'mediable')->whereGif();
    }

    public function muscles()
    {
        return $this->belongsToMany(Muscle::class);
    }

    public function challenges()
    {
        return $this->belongsToMany(Challenge::class);
    }

    public function groupTags()
    {
        return $this->belongsToMany(Tag::class, 'exercise_group_tag')->whereType(TypeTag::GroupExercise);
    }

    public function createdBy()
    {
        $user =  $this->belongsTo(User::class, 'created_by');
        if ($user->first()->belongsRoles([Role::admin, Role::creator, Role::superAdmin])) {
            return $user;
        } else {
            throw new Exception("user created this exercise who isn't creator", 1);
        }
    }

    public function sessionExercises()
    {
        return $this->hasMany(SessionExercise::class);
    }

    //=========== create scope ==========
    //===================================
}
