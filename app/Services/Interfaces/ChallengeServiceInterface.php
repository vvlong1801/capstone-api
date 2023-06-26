<?php

namespace App\Services\Interfaces;


interface ChallengeServiceInterface
{
    public function getChallenges();
    public function getChallengeTags();
    public function getChallengeById($id);
    public function getChallengeStatistics($id);
    public function getChallengeFeedbacks($id);
    public function confirmNewChallenge($id, $payload);
    public function createChallenge(array $payload);
    public function deleteChallenge($id);
}
