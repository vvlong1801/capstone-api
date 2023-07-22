<?php

namespace App\Services;

use App\Enums\Role;
use App\Models\User;
use App\Models\Challenge;
use App\Models\WorkoutUser;
use App\Models\Creator;
use App\Models\Exercise;
use Carbon\Carbon;
use App\Services\Interfaces\DashboardServiceInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DashboardService extends BaseService implements DashboardServiceInterface
{
    // public function getNewMembersOfMonth()
    // {
    //     $firstDay = Carbon::now()->startOfMonth();
    //     $endDay = Carbon::now()->endOfMonth();

    //     $user = User::whereBetween('email_verified_at', [$firstDay, $endDay]);

    //     $users = $user->whereHas('account', function (Builder $query) {
    //         $query->whereIn('role', [Role::creator, Role::workoutUser]);
    //     });

    //     return $users->paginate(10);
    // }

    // public function getChallengesOfLastSomeDay(int $count)
    // {
    //     $challenges = Challenge::where('created_at', '>=', Carbon::now()->subDays($count))->paginate(10);

    //     return $challenges;
    // }

    // public function getAllCurrentMembers($role = null)
    // {
    //     $users = User::query();
    //     if ($role) {
    //         $users = $users->whereHas('account', function (Builder $query) use ($role) {
    //             $query->where('role', $role == 'creator' ? Role::creator : Role::workoutUser);
    //         });
    //     }

    //     return $users->get();
    // }

    // public function getChallengeMembers(int $id)
    // {
    //     $user = DB::table('users')
    //         ->join('challenge_members', 'users.id', '=', 'challenge_members.user_id')
    //         ->join('challenge_phases', 'challenge_members.challenge_id', '=', 'challenge_phases.challenge_id')
    //         ->join('challenges', 'challenge_members.challenge_id', '=', 'challenges.id')
    //         // ->join('phase_sessions', 'challenge_phases.id', '=', 'phase_sessions.challenge_phase_id')
    //         // ->join('exercise_phase_session', 'phase_sessions.id', '=', 'exercise_phase_session.phase_session_id')
    //         ->where('challenge_members.challenge_id', $id)
    //         ->groupBy('users.id')
    //         ->select('users.id', 'users.name', 'users.email', 'challenge_members.updated_at as joined_date', 'challenge_members.status as joined_status', DB::raw('SUM(challenge_phases.total_days) as challenge_days'), 'challenges.start_at as challenge_started_date')
    //         ->get();

    //     return $user;
    // }


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
