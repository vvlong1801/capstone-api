<?php

namespace App\Services\Interfaces;

interface DashboardServiceInterface
{
    public function getNewMembersOfMonth();
    public function getChallengesOfLastSomeDay(int $count);
    public function getAllCurrentMembers($role = null);
    public function getTopChallenges(int $k, $sort);
    public function getChalengeMembers(int $id);
}
