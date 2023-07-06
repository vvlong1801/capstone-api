<?php

namespace App\Services;

use App\Enums\Role;
use App\Models\User;
use App\Models\Challenge;
use Carbon\Carbon;
use App\Services\Interfaces\DashboardServiceInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DashboardService extends BaseService implements DashboardServiceInterface
{
    public function getNewMembersOfMonth()
    {
        $firstDay = Carbon::now()->startOfMonth();
        $endDay = Carbon::now()->endOfMonth();

        $user = User::whereBetween('email_verified_at', [$firstDay, $endDay]);

        $users = $user->whereHas('account', function (Builder $query) {
            $query->whereIn('role', [Role::creator, Role::workoutUser]);
        });

        return $users->paginate(10);
    }

    public function getChallengesOfLastSomeDay(int $count)
    {
        $challenges = Challenge::where('created_at', '>=', Carbon::now()->subDays($count))->paginate(10);

        return $challenges;
    }

    public function getTopChallenges(int $k, $sort)
    {
        $query = Challenge::withCount('members')->orderBy('members_count', $sort)->take($k)->get();

        return $query;
    }

    public function getAllCurrentMembers($role = null)
    {
        $users = User::query();
        if ($role) {
            $users = $users->whereHas('account', function (Builder $query) use ($role) {
                $query->where('role', $role == 'creator' ? Role::creator : Role::workoutUser);
            });
        }

        return $users->get();
    }

    // 1 challenge có nhiều challeng phase, 1 challenge phase có nhiều session, 1 session lại có nhiều exercise
    public function getChalengeMembers(int $id)
    {
        $user = DB::table('users')
                ->join('challenge_members', 'users.id', '=', 'challenge_members.user_id')
                ->join('challenge_phases', 'challenge_members.challenge_id', '=', 'challenge_phases.challenge_id')
                ->join('challenges', 'challenge_members.challenge_id', '=', 'challenges.id')
                // ->join('phase_sessions', 'challenge_phases.id', '=', 'phase_sessions.challenge_phase_id')
                // ->join('exercise_phase_session', 'phase_sessions.id', '=', 'exercise_phase_session.phase_session_id')
                ->where('challenge_members.challenge_id', $id)
                ->groupBy('users.id')
                ->select('users.id', 'users.name', 'users.email', 'challenge_members.updated_at as joined_date', 'challenge_members.status as joined_status', DB::raw('SUM(challenge_phases.total_days) as challenge_days'), 'challenges.start_at as challenge_started_date')
                ->get();

        return $user;
    }
}
