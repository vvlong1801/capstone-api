<?php

namespace App\Http\Controllers\WorkoutUser;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\Analysis\WorkoutUserAnalysisServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(WorkoutUserAnalysisServiceInterface $analysisService)
    {
        $workoutDays = $analysisService->trackWorkoutDay(Auth::user());
        $calInDay = $analysisService->getTotalCalBurnedGroupByDay(Auth::user());
        $timeInDay = $analysisService->getTotalWorkoutTimeGroupByDay(Auth::user());
        $totalWorkoutTime = $analysisService->getTotalWorkoutTime(Auth::user());
        $sessionCount = $analysisService->countSession(Auth::user());
        $challengeCount = $analysisService->countChallenge(Auth::user());
        $res = [
            'workout_days' => $workoutDays,
            'cal_in_day' => $calInDay,
            'time_in_day' => $timeInDay,
            'total_time' => $totalWorkoutTime,
            'session_count' => $sessionCount,
            'challenge_count' => $challengeCount,
        ];
        return $this->responseOk($res);
    }
}
