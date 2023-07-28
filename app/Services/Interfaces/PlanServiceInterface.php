<?php

namespace App\Services\Interfaces;

interface PlanServiceInterface
{
    public function getPlanChallenges();
    public function getPlanById($id);
    public function createPlan($challengeId);
    public function createPlanSession($payload);
    public function getFeedbacksByPlanId($planId);
}
