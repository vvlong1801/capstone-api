<?php

namespace App\Services;

use App\Enums\RoleChallenge;
use App\Models\Challenge;
use App\Models\ChallengeInvitation;
use App\Services\Interfaces\ChallengeMemberServiceInterface;
use Illuminate\Support\Facades\DB;

class ChallengeMemberService extends BaseService implements ChallengeMemberServiceInterface
{
    public function createChallengeMember($userId, $challengeId)
    {
        // check accept rule of challenge
        DB::beginTransaction();
        try {
            $challenge = Challenge::find($challengeId);
            $existedInvitation = ChallengeInvitation::where('challenge_id', $challengeId)->where('user_id', $userId)->count();
            $status = $existedInvitation || $challenge->accept_all;
            DB::table('challenge_members')->insert([
                'challenge_id' => $challengeId,
                'user_id' => $userId,
                'status' => $status,
                'role' => RoleChallenge::member,
                'created_at' => \Carbon\Carbon::now()
            ]);
            DB::commit();
            return $challenge;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
