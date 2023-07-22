<?php

namespace App\Services\Interfaces;


interface ChallengeInvitationServiceInterface
{
    public function getInvitationByUserId($userId);
    public function getInvitationByChallengeId($id);
    public function createInvitation($challenge, $payload);
    public function deleteInvitation($userId, $challengeId);
}
