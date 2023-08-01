<?php

namespace App\Services;

use App\Enums\RoleChallenge;
use App\Models\Challenge;
use App\Models\ChallengeInvitation;
use App\Models\SessionResult;
use App\Services\Interfaces\ChallengeMemberServiceInterface;
use Carbon\CarbonInterval;
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

    public function sumCalInDay($planId)
    {
        $calInday = SessionResult::selectRaw('DATE_FORMAT(created_at, "%d-%m-%Y") AS day, SUM(calories_burned) AS cal_sum')
            ->where('plan_id', $planId)->groupBy('day')->orderBy('day')->get();
        return $calInday;
    }

    public function sumTimeInDay($planId)
    {
        $timeInday = SessionResult::selectRaw('DATE_FORMAT(created_at, "%d-%m-%Y") AS day, duration')
            ->where('plan_id', $planId)->get()->groupBy('day')->map(function ($group) {
                $totalDuration = $group->sum(function ($item) {
                    return strtotime($item['duration']) - strtotime('00:00:00');
                });
                return [
                    'day' => $group->first()['day'],
                    'total_duration' => CarbonInterval::seconds($totalDuration)->cascade()->format('%H:%I:%S'),
                ];
            })->values();
        return $timeInday;
    }
}
