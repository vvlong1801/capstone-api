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

    /**
     * Display a new members of this month
     */
    public function newMembers()
    {
        $users = $this->dashboardService->getNewMembersOfMonth();
        return $this->responseOk(UserResource::collection($users));
    }

    /**
     * Display a new challenges of this month
     */
    public function newChallenges()
    {
        $challenges = $this->dashboardService->getChallengesOfLastSomeDay(7);
        return $this->responseOk(ChallengeResource::collection($challenges));
    }

    /**
     * Display a top challenges
     */
    public function topChallenges()
    {
        $challenges = $this->dashboardService->getTopChallenges(5, 'desc');
        
        return $this->responseOk(ChallengeResource::collection($challenges));
    }

    // all chalenges
    public function getChallenges()
    {
        $challenges = $this->challengeService->getChallenges();

        return $this->responseOk(ChallengeResource::collection($challenges));
    }

    // all current members
    public function getMembers(Request $request)
    {
        $role = $request->get('role') ? $request->get('role') : null;
        $users = $this->dashboardService->getAllCurrentMembers($role);

        return $this->responseOk(UserResource::collection($users));
    }

    /**
     * Display a list members of challenge.
     */
    public function getMembersOfChallenge($id)
    {
       $members = $this->dashboardService->getChalengeMembers($id);

       return $this->responseOk(DashboardUserResource::collection($members));
    }
}
