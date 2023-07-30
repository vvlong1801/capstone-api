<?php

namespace App\Services\Analysis;

use App\Enums\Role;
use App\Models\User;
use App\Models\Challenge;
use App\Models\WorkoutUser;
use App\Models\Creator;
use App\Models\Exercise;
use App\Services\BaseService;
use App\Services\Interfaces\Analysis\AdminAnalysisServiceInterface;
use Illuminate\Support\Facades\DB;

class AdminAnalysisService extends BaseService implements AdminAnalysisServiceInterface
{
    public function countWorkoutUsers()
    {
        return WorkoutUser::where('status', 1)->count();
    }

    public function countCreators()
    {
        return Creator::all()->count();
    }

    public function countChallenges()
    {
        return Challenge::all()->count();
    }

    public function countExercises()
    {
        return Exercise::all()->count();
    }

    public function getTopChallenges(int $k, $sort)
    {
        $query = Challenge::with('mainImage')->withCount('members')->orderBy('members_count', $sort)->take($k)->get();

        return $query;
    }

    public function getTopCreators(int $k)
    {
        $challengeCollection = Challenge::withCount('members')->whereRelation('createdBy.account', 'role', Role::creator)->get();
        $mappedCollection = $challengeCollection->groupBy('created_by')->map(function ($items) {
            return collect($items)->sum('members_count');
        });

        return $mappedCollection->sortDesc()->take($k)->map(function ($value, $index) {
            return [
                'creator' => User::where('id', $index)->whereRelation('account', 'role', Role::creator)->first(),
                'total_members' => $value,
            ];
        })->values();
    }

    public function getWorkoutUserGroupByMonth()
    {
        $result = DB::table('workout_users')->selectRaw('DATE_FORMAT(created_at, "%m-%Y") AS month, COUNT(*) AS count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $result;
    }

    public function getCreatorGroupByMonth()
    {
        $result = DB::table('creators')->selectRaw('DATE_FORMAT(created_at, "%m-%Y") AS month, COUNT(*) AS count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $result;
    }
    public function getChallengeGroupByMonth()
    {
        $result = DB::table('challenges')->selectRaw('DATE_FORMAT(created_at, "%m-%Y") AS month, COUNT(*) AS count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $result;
    }
    public function getExerciseGroupByMonth()
    {
        $result = DB::table('exercises')->selectRaw('DATE_FORMAT(created_at, "%m-%Y") AS month, COUNT(*) AS count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $result;
    }
}
