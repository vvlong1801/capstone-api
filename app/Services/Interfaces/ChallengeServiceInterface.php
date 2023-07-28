<?php

namespace App\Services\Interfaces;

use App\Models\Challenge;

interface ChallengeServiceInterface
{
    public function getChallenges();
    public function getChallengeTags();
    public function getChallengeById($id);
    public function getChallengeTemplateById($id);
    public function getFeedbacksByChallengeId($challengeId);
    public function getCommentsByChallengeId($challengeId);
    // public function getChallengeStatistics($id);
    // public function getChallengeFeedbacks($id);
    public function createChallengeMember($id);
    // public function createChallengeInvitation(Challenge $challenge, $payload);
    public function confirmNewChallenge($id, $payload);
    public function updateChallengeInformation($id, $payload);
    public function approveChallenge($id);
    public function createChallenge(array $payload);
    public function deleteChallenge($id);
}
