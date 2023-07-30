<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\Analysis\CreatorAnalysisServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CreatorAnalysisServiceInterface $creatorAnalysis)
    {
        $rateCount = $creatorAnalysis->countRating(Auth::user());
        $sessionResultCount = $creatorAnalysis->countSessionResults(Auth::user());
        $challengeCount = $creatorAnalysis->countChallenges(Auth::user());
        $memberInMonth = $creatorAnalysis->countMemberGroupByMonth(Auth::user());
        $resultInMonth = $creatorAnalysis->countSessionResultByMonth(Auth::user());
        $memberInChallenge = $creatorAnalysis->countMemberGroupByChallenge(Auth::user());
        $resultInChallenge = $creatorAnalysis->countSessionResultGroupByChallenge(Auth::user());
        $response = [
            'member_count' => Auth::user()->creator->members,
            'rate_count' => $rateCount,
            'session_result_count' => $sessionResultCount,
            'challenge_count' => $challengeCount,
            'member_in_month' => $memberInMonth,
            'result_in_month' => $resultInMonth,
            'member_in_challenge' => $memberInChallenge,
            'result_in_challenge' => $resultInChallenge,
        ];

        return $this->responseOk($response);
    }
}
