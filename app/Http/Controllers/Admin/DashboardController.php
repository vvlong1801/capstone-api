<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\ChallengeResource;
use App\Http\Resources\DashboardUserResource;
use App\Services\Interfaces\DashboardServiceInterface;
use App\Services\Interfaces\UserService;
use App\Services\Interfaces\ChallengeServiceInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;
    protected $challengeService;
    public function __construct(DashboardServiceInterface $dashboardService, ChallengeServiceInterface $challengeService)
    {
        $this->dashboardService = $dashboardService;
        $this->challengeService = $challengeService;
    }

    // /**
    //  * Display a new members of this month
    //  */
    // public function newMembers()
    // {
    //     $users = $this->dashboardService->getNewMembersOfMonth();
    //     return $this->responseOk(UserResource::collection($users));
    // }

    // /**
    //  * Display a new challenges of this month
    //  */
    // public function newChallenges()
    // {
    //     $challenges = $this->dashboardService->getChallengesOfLastSomeDay(7);
    //     return $this->responseOk(ChallengeResource::collection($challenges));
    // }

    // /**
    //  * Display a top challenges
    //  */
    // public function topChallenges()
    // {
    //     $challenges = $this->dashboardService->getTopChallenges(5, 'desc');

    //     return $this->responseOk(ChallengeResource::collection($challenges));
    // }

    // // all chalenges
    // public function getChallenges()
    // {
    //     $challenges = $this->challengeService->getChallenges();

    //     return $this->responseOk(ChallengeResource::collection($challenges));
    // }

    // // all current members
    // public function getMembers(Request $request)
    // {
    //     $role = $request->get('role') ? $request->get('role') : null;
    //     $users = $this->dashboardService->getAllCurrentMembers($role);

    //     return $this->responseOk(UserResource::collection($users));
    // }

    // /**
    //  * Display a list members of challenge.
    //  */
    // public function getMembersOfChallenge($id)
    // {
    //     $members = $this->dashboardService->getChallengeMembers($id);

    //     return $this->responseOk(DashboardUserResource::collection($members));
    // }

    // public function getOverview(Request $request)
    // {
    //     $data = $this->dashboardService->countObj();

    //     return $this->responseOk($data);
    // }

    public function analysis()
    {
        $topChallenges = $this->dashboardService->getTopChallenges(3, 'desc');
        $topCreators = $this->dashboardService->getTopCreators(3);

        $countCreators = $this->dashboardService->countCreators();
        $countWorkoutUsers = $this->dashboardService->countWorkoutUsers();
        $countChallenges = $this->dashboardService->countChallenges();
        $countExercises = $this->dashboardService->countExercises();

        $workoutUserInMonth = $this->dashboardService->getWorkoutUserGroupByMonth();
        $creatorInMonth = $this->dashboardService->getCreatorGroupByMonth();
        $challengeInMonth = $this->dashboardService->getChallengeGroupByMonth();
        $exerciseInMonth = $this->dashboardService->getExerciseGroupByMonth();

        $topCreators = $topCreators->map(function ($item) {
            $item['creator'] = new UserResource($item['creator']);
            return $item;
        });

        $response = [
            'creators_count' => $countCreators,
            'workout_users_count' => $countWorkoutUsers,
            'challenges_count' => $countChallenges,
            'exercises_count' => $countExercises,
            'top_creators' => $topCreators,
            'top_challenges' => ChallengeResource::collection($topChallenges),
            'workout_user_in_month' => $workoutUserInMonth,
            'creator_in_month' => $creatorInMonth,
            'challenge_in_month' => $challengeInMonth,
            'exercise_in_month' => $exerciseInMonth,
        ];

        return $this->responseOk($response);
    }

    // /**
    //  * Display a top challenges
    //  */
    // public function topCreators()
    // {
    //     $topCreators = $this->dashboardService->getTopCreators(3);

    //     return $this->responseOk($topCreators);
    // }
}
