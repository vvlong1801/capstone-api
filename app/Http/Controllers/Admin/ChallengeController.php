<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConfirmChallengeRequest;
use App\Http\Requests\Admin\ConfirmNewChallengeRequest;
use App\Models\Challenge;
use App\Notifications\ApproveChallenge;
use App\Services\Interfaces\ChallengeInvitationServiceInterface;
use App\Services\Interfaces\ChallengeServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ChallengeController extends Controller
{
    protected $challengeService;
    protected $challengeInvitationService;

    public function __construct(ChallengeServiceInterface $challengeService, ChallengeInvitationServiceInterface $challengeInvitationService)
    {
        $this->challengeService = $challengeService;
        $this->challengeInvitationService = $challengeInvitationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function confirmNewChallenge($id, ConfirmNewChallengeRequest $request)
    {
        $payload = $request->validated();

        try {
            if ($payload['approve']) {
                $challenge = $this->challengeService->approveChallenge($id);
                //notify invitation
                $invitations = $this->challengeInvitationService->getInvitationByChallengeId($id);
                // Notification::send()
                // foreach ($invitations as $key => $invitation) {
                //     Notification::send($invitation->user, new InviteJoinChallenge($invitation, true));
                // }
                //notify creator
                Notification::send($challenge->createdBy, new ApproveChallenge($challenge, true));
            } else {
                $challenge = Challenge::find($id);
                Notification::send($challenge->createdBy, new ApproveChallenge($challenge, false));
            }
            return $this->responseNoContent('confirm success');
        } catch (\Throwable $th) {
            throw $th;
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
        //
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
