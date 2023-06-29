<?php

namespace App\Services;

use App\Enums\RoleChallenge;
use App\Models\ChallengeInvitation;
use App\Services\Interfaces\ChallengeInvitationServiceInterface;
use Illuminate\Support\Facades\Date;

class ChallengeInvitationService extends BaseService implements ChallengeInvitationServiceInterface
{
    public function getInvitationByChallengeId($id)
    {
        return ChallengeInvitation::where('challenge_id', $id)->get();
    }

    public function createInvitation($challenge, $payload)
    {
        \DB::beginTransaction();
        try {
            $invitations = \Arr::map($payload, function ($item) {
                $item['role'] = RoleChallenge::fromName(\Str::lower($item['role']));
                $item['expires_at'] = Date::now()->addDay(3);
                return $item;
            });
            $challenge->invitations()->createMany($invitations);
            \DB::commit();
        } catch (\Throwable $th) {
            \DB::rollback();
            throw $th;
        }
    }
}
