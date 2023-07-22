<?php

namespace App\Services;

use App\Enums\RoleChallenge;
use App\Models\Challenge;
use App\Models\ChallengeInvitation;
use App\Services\Interfaces\ChallengeInvitationServiceInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class ChallengeInvitationService extends BaseService implements ChallengeInvitationServiceInterface
{
    public function getInvitationByChallengeId($id)
    {
        return ChallengeInvitation::where('challenge_id', $id)->get();
    }

    public function getInvitationByUserId($userId)
    {
        $challengeIds = ChallengeInvitation::where('user_id', $userId)->whereDate('expires_at', '>', \Carbon\Carbon::now())->pluck('challenge_id')->toArray();
        $challenges = Challenge::with(['mainImage', 'createdBy', 'tags', 'images'])->withCount(['phases'])
            ->withSum('phases as total_sessions', 'total_days')->whereIn('id', $challengeIds)->get();
        return $challenges;
    }

    public function createInvitation($challenge, $payload)
    {
        DB::beginTransaction();
        try {
            $invitations = Arr::map($payload, function ($item) {
                $item['role'] = RoleChallenge::fromName(\Str::lower($item['role']));
                $item['expires_at'] = Date::now()->addDay(3);
                return $item;
            });
            $challenge->invitations()->createMany($invitations);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function deleteInvitation($userId, $challengeId)
    {
        ChallengeInvitation::where('user_id', $userId)->where('challenge_id', $challengeId)->delete();
    }
}
