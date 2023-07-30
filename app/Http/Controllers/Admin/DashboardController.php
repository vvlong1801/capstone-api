<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\ChallengeResource;
use App\Services\Interfaces\Analysis\AdminAnalysisServiceInterface;
use App\Services\Interfaces\ChallengeServiceInterface;

class DashboardController extends Controller
{
    protected $analysisService;
    protected $challengeService;
    public function __construct(AdminAnalysisServiceInterface $analysisService, ChallengeServiceInterface $challengeService)
    {
        $this->analysisService = $analysisService;
        $this->challengeService = $challengeService;
    }

    public function analysis()
    {
        $topChallenges = $this->analysisService->getTopChallenges(3, 'desc');
        $topCreators = $this->analysisService->getTopCreators(3);

        $countCreators = $this->analysisService->countCreators();
        $countWorkoutUsers = $this->analysisService->countWorkoutUsers();
        $countChallenges = $this->analysisService->countChallenges();
        $countExercises = $this->analysisService->countExercises();

        $workoutUserInMonth = $this->analysisService->getWorkoutUserGroupByMonth();
        $creatorInMonth = $this->analysisService->getCreatorGroupByMonth();
        $challengeInMonth = $this->analysisService->getChallengeGroupByMonth();
        $exerciseInMonth = $this->analysisService->getExerciseGroupByMonth();

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
}
