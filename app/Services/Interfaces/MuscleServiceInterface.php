<?php

namespace App\Services\Interfaces;

interface MuscleServiceInterface
{
    public function getMuscles();
    public function getMusclesWithPagination();
    // public function findById(int $id);
    public function createMuscle(array $payload);
    public function updateMuscle($id, array $payload);
    public function deleteMuscle($id);
}
