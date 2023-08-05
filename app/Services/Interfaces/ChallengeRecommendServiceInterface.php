<?php

namespace App\Services\Interfaces;

interface ChallengeRecommendServiceInterface
{
    public function recommend($userId, $challenges);
}
