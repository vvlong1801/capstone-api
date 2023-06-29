<?php

namespace App\Models;

use App\Enums\RoleChallenge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeInvitation extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'role' => RoleChallenge::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}
