<?php

namespace App\Services\Interfaces\Analysis;

interface AdminAnalysisServiceInterface
{
    public function getTopChallenges(int $k, $sort);
    public function getTopCreators(int $k);
    public function countWorkoutUsers();
    public function countCreators();
    public function countChallenges();
    public function countExercises();
    public function getWorkoutUserGroupByMonth();
    public function getCreatorGroupByMonth();
    public function getChallengeGroupByMonth();
    public function getExerciseGroupByMonth();
}
