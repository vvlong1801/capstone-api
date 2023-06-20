<?php

namespace App\Services\Interfaces;


interface ChallengeServiceInterface
{
    public function getChallenges();
    public function getChallengeById($id);
    public function createChallenge(array $payload);
    public function updateChallenge($id, array $payload);
    public function deleteChallenge($id);
}
