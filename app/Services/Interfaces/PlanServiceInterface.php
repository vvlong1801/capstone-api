<?php

namespace App\Services\Interfaces;

interface PlanServiceInterface
{
    public function getPlans();
    public function createPlan($challengeId);
}
