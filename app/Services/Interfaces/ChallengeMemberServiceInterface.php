<?php

namespace App\Services\Interfaces;

use App\Models\Challenge;

interface ChallengeMemberServiceInterface
{
    public function createChallengeMember($userId,$challengeId);
    public function sumCalInDay($planId);
    public function sumTimeInDay($planId);
}
