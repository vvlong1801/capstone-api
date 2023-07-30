<?php

namespace App\Services\Interfaces\Analysis;

interface WorkoutUserAnalysisServiceInterface
{
    public function trackWorkoutDay($workoutUser);
    public function getTotalCalBurnedGroupByDay($workoutUser);
    public function getTotalWorkoutTimeGroupByDay($workoutUser);
    public function getTotalWorkoutTime($workoutUser);
    public function countSession($workoutUser);
    public function countChallenge($workoutUser);
}
