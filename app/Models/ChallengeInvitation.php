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
}
