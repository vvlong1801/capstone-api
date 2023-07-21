<?php

namespace App\Services\Interfaces;

interface ProfileServiceInterface
{
    public function getGoals();
    public function getCertificateIssuers();
    public function updateWorkoutUserProfile($id, $payload);
    public function updateCreatorProfile($id, $payload);
    public function updatePersonalTrainerProfile($id, $payload);
    public function getProfileWorkoutUserByUserId($userId);
    public function getProfileCreatorByUserId($userId);
}
