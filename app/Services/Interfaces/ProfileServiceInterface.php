<?php

namespace App\Services\Interfaces;

interface ProfileServiceInterface
{
    public function getGoals();
    public function updateProfile($id, $payload);
    public function getProfileByUserId($userId);
}
