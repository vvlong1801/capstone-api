<?php

namespace App\Services\Interfaces;

interface RecommendServiceInterface
{
    public function recommendChallenges($type, $ids);
}
