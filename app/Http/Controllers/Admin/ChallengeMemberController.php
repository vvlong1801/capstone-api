<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusChallengeMember;
use App\Http\Controllers\Controller;
use App\Http\Resources\Creator\ChallengeMemberResource;
use App\Http\Resources\SessionResultResource;
use App\Http\Resources\WorkoutUser\ProfileResource;
use App\Models\Plan;
use App\Models\SessionResult;
use App\Models\WorkoutUser;
use App\Services\Interfaces\ChallengeMemberServiceInterface;
use App\Services\Interfaces\PlanServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChallengeMemberController extends Controller
{
    protected $challengeMemberService;
    public function __construct(ChallengeMemberServiceInterface $challengeMemberService)
    {
        $this->challengeMemberService = $challengeMemberService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = DB::table('challenge_members')
            ->select(
                'users.name as user_name',
                'users.email',
                'users.id as user_id',
                'challenges.name as challenge_name',
                'challenge_members.status',
                'challenge_members.id',
                'workout_users.gender',
                'workout_users.level',
                'challenge_members.created_at'
            )
            ->join('users', 'users.id', '=', 'user_id')
            ->join('workout_users', 'workout_users.user_id', '=', 'users.id')
            ->join('challenges', 'challenges.id', '=', 'challenge_id')->distinct()->get();
        return $this->responseOk(ChallengeMemberResource::collection($members));
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = DB::table('challenge_members')->select('challenge_id', 'user_id')->whereId($id)->first();
        $workoutUser = WorkoutUser::with(['user', 'user.avatar'])->where('user_id', $result->user_id)->first();
        $plan = Plan::where('user_id', $result->user_id)->where('challenge_id', $result->challenge_id)->first();
        $sessions = SessionResult::with(['phaseSession', 'feedbacks'])->where('plan_id', $plan->id)->get();
        $calInDay = $this->challengeMemberService->sumCalInDay($plan->id);
        $timeInDay = $this->challengeMemberService->sumTimeInDay($plan->id);
        $res = [
            'last_workout_day' => $plan->lastWorkoutDay,
            'session_count' => $plan->sessionResultCount,
            'current_session' => $plan->current_session,
            'cal_in_day' => $calInDay,
            'time_in_day' => $timeInDay,
            'sessions' => SessionResultResource::collection($sessions),
            'workout_user' => new ProfileResource($workoutUser),
        ];
        return $this->responseOk($res);
    }

    public function approve($memberId, PlanServiceInterface $planService){
        DB::table("challenge_members")->where('id', $memberId)->update(['status' => StatusChallengeMember::approved]);
        $result = DB::table("challenge_members")->select('user_id', 'challenge_id')->where('id', $memberId)->first();
        $planService->createPlan($result->user_id, $result->challenge_id);
        return $this->responseNoContent('approve success');
    }
}
