<?php

namespace App\Services\Interfaces;

interface DashboardServiceInterface
{
    // public function getNewMembersOfMonth();
    // public function getChallengesOfLastSomeDay(int $count);
    // public function getAllCurrentMembers($role = null);
    // public function getChallengeMembers(int $id);
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
