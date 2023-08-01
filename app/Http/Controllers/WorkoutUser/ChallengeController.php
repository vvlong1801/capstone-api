<?php

namespace App\Http\Controllers\WorkoutUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkoutUser\RateChallengeRequest;
use App\Http\Resources\ChallengeResource;
use App\Http\Resources\MessageResource;
use App\Models\Challenge;
use App\Models\Message;
use App\Models\Plan;
use App\Models\Rating;
use App\Notifications\FeedbackWorkout;
use App\Notifications\NewChallengeMember;
use App\Notifications\NewChallengeRating;
use App\Services\Interfaces\ChallengeInvitationServiceInterface;
use App\Services\Interfaces\ChallengeMemberServiceInterface;
use App\Services\Interfaces\ChallengeServiceInterface;
use App\Services\Interfaces\PlanServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

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
            $challenge = $this->challengeMemberService->createChallengeMember(Auth::user()->id, $id);
            if ($challenge->accept_all) {
                $planService->createPlan(Auth::user()->id, $id);
                // notify to creator of the challenge
                $response = ['approved' => true];
            } else {
                // notify to creator of the challenge
                Notification::send($challenge->createdBy, new NewChallengeMember(Auth::user(), $challenge));
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
            $planService->createPlan($userId, $id);
            $challengeInvitationService->deleteInvitation($userId, $id);
            // response to user
            return $this->responseNoContent('accept challenge success');
        } catch (\Throwable $th) {
            abort(500, 'server error');
        }
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

    public function rate(RateChallengeRequest $request)
    {
        $payload = $request->validated();

        $challenge = Plan::find($payload['plan_id'])->challenge;
        // store rate & feedback of challenge
        $rate = Rating::create([
            'rateable_type' => Challenge::class,
            'rateable_id' => $challenge->id,
            'value' => $payload['rate'],
            'user_id' => Auth::user()->id,
        ]);

        Notification::send($challenge->createdBy, new NewChallengeRating($challenge, $rate));

        return $this->responseNoContent('success');
    }

    public function comment($challengeId, Request $request)
    {

        Challenge::where('id', $challengeId)->whereExists(function ($query) use ($request, $challengeId) {
            if ($request['content']) {
                Message::create([
                    'messageable_type' => Challenge::class,
                    'messageable_id' => $challengeId,
                    'content' => $request['content'],
                    'group' => true,
                    'sender_id' => Auth::user()->id,
                ]);
            }
        });
    }

    public function getComments($challengeId)
    {
        $comments =  Message::where('messageable_type', Challenge::class)->where('messageable_id', $challengeId)->orderBy('created_at', 'desc')->get();
        return $this->responseOk(MessageResource::collection($comments), 'get commments ok');
    }
}
