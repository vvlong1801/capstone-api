<?php

namespace App\Services\Interfaces;


interface ChallengeInvitationServiceInterface
{
    public function getInvitationByChallengeId($id);
    public function createInvitation($challenge, $payload);
}
