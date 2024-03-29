<?php

namespace App\Models;

use App\Enums\MediaCollection;
use App\Models\Traits\HasPermissions;
use App\Models\Traits\HasRoles;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as AuthMustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements AuthMustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles, HasPermissions;
    use MustVerifyEmail;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'first_login',
        'account_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function avatar()
    {
        return $this->morphOne(Media::class, 'mediable')->whereImage()->where('collection_name', MediaCollection::Avatar);
    }

    public function workoutUser()
    {
        return $this->hasOne(WorkoutUser::class);
    }

    public function creator()
    {
        return $this->hasOne(Creator::class);
    }

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function goals()
    {
        return $this->belongsToMany(Goal::class);
    }

    public function challenges()
    {
        return $this->hasMany(Challenge::class, 'created_by');
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class, 'created_by');
    }
}
