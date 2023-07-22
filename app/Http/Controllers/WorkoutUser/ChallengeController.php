<?php

namespace App\Http\Controllers\WorkoutUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChallengeResource;
use App\Services\Interfaces\ChallengeInvitationServiceInterface;
use App\Services\Interfaces\ChallengeMemberServiceInterface;
use App\Services\Interfaces\ChallengeServiceInterface;
use App\Services\Interfaces\PlanServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChallengeController extends Controller
{
    protected $challengeService;
    protected $challengeMemberService;

    public function __construct(ChallengeServiceInterface $challengeService, ChallengeMemberServiceInterface $challengeMemberService)
    {
        $this->challengeService = $challengeService;
        $this->challengeMemberService = $challengeMemberService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $challenges = $this->challengeService->getChallenges();
        return $this->responseOk(ChallengeResource::collection($challenges), 'get challenges is success');
    }

    public function getChallengeInvitations(ChallengeInvitationServiceInterface $challengeInvitationService)
    {
        $challenges = $challengeInvitationService->getInvitationByUserId(Auth::user()->id);

        return $this->responseOk(ChallengeResource::collection($challenges), 'get challenge invitation success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function join($id, PlanServiceInterface $planService)
    {
        // create challenge member transaction with check accept rule
        try {
            $status = $this->challengeMemberService->createChallengeMember(Auth::user()->id, $id);
            if ($status) {
                $planService->createPlan($id);
                // notify to creator of the challenge
                $response = ['approved' => true];
            } else {
                // notify to creator of the challenge
                $response = ['approved' => false];
            }

            // response to user
            return $this->responseOk($response, 'request join challenge success');
        } catch (\Throwable $th) {
            abort(500, 'server error');
        }
    }

    public function acceptInvitation($id, PlanServiceInterface $planService, ChallengeInvitationServiceInterface $challengeInvitationService)
    {
        try {
            $userId = Auth::user()->id;
            $this->challengeMemberService->createChallengeMember($userId, $id);
            $planService->createPlan($id);
            $challengeInvitationService->deleteInvitation($userId, $id);
            // response to user
            return $this->responseNoContent('accept challenge success');
        } catch (\Throwable $th) {
            abort(500, 'server error');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $challenge = $this->challengeService->getChallengeById($id);
        if (!$challenge) abort(404, 'not founded this challenge');
        return $this->responseOk(new ChallengeResource($challenge), 'get challenge is success');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
