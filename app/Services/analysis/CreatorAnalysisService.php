<?php

namespace App\Services\Analysis;

use App\Enums\StatusChallengeMember;
use App\Models\Challenge;
use App\Models\Plan;
use App\Models\Rating;
use App\Models\SessionResult;
use App\Services\BaseService;
use App\Services\Interfaces\Analysis\CreatorAnalysisServiceInterface;
use Illuminate\Support\Facades\DB;

class CreatorAnalysisService extends BaseService implements CreatorAnalysisServiceInterface
{
    public function countMembers()
    {
    }
    public function countRating($creator)
    {
        $challengeIds = Challenge::where('created_by', $creator->id)->pluck('id');
        $rateCount = Rating::where('rateable_type', Challenge::class)->whereIn('rateable_id', $challengeIds)->count();
        return $rateCount;
    }

    public function countSessionResults($creator)
    {
        $challengeIds = Challenge::where('created_by', $creator->id)->pluck('id');
        $planIds = Plan::whereIn('challenge_id', $challengeIds)->pluck('id');
        $sessionResultCount = SessionResult::whereIn('plan_id', $planIds)->count();
        return $sessionResultCount;
    }
    public function countChallenges($creator)
    {
        return Challenge::where('created_by', $creator->id)->count();
    }

    public function countMemberGroupByMonth($creator)
    {
        $challengeIds = Challenge::where('created_by', $creator->id)->pluck('id');
        return DB::table('challenge_members')->selectRaw('DATE_FORMAT(created_at, "%m-%Y") AS month, COUNT(*) AS count')
            ->whereIn('challenge_id', $challengeIds)->where('status', StatusChallengeMember::approved)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function countSessionResultByMonth($creator)
    {
        $challengeIds = Challenge::where('created_by', $creator->id)->pluck('id');
        $planIds = Plan::whereIn('challenge_id', $challengeIds)->pluck('id');
        return DB::table('session_results')->selectRaw('DATE_FORMAT(created_at, "%m-%Y") AS month, COUNT(*) AS count')
            ->whereIn('plan_id', $planIds)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function countMemberGroupByChallenge($creator)
    {
        $challengeIds = Challenge::where('created_by', $creator->id)->pluck('id');
        // dd($challengeIds);
        return DB::table('challenge_members')->selectRaw('challenges.name, challenge_id, COUNT(*) AS count')
            ->join('challenges', 'challenge_id', '=', 'challenges.id')
            ->whereIn('challenge_id', $challengeIds)->where('challenge_members.status', StatusChallengeMember::approved)
            ->groupBy('challenge_id')
            ->orderBy('count')
            ->get();
    }

    public function countSessionResultGroupByChallenge($creator)
    {
        $challengeIds = Challenge::where('created_by', $creator->id)->pluck('id');
        $planIds = Plan::whereIn('challenge_id', $challengeIds)->pluck('id');
        return DB::table('session_results')->selectRaw('challenges.name, COUNT(*) AS count')
            ->join('plans', 'session_results.plan_id', '=', 'plans.id')
            ->join('challenges', 'plans.challenge_id', '=', 'challenges.id')
            ->whereIn('session_results.plan_id', $planIds)
            ->groupBy('challenges.name')
            ->orderBy('count')
            ->get();
    }
}
