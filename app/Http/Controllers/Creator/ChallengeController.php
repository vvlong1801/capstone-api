<?php

namespace App\Http\Controllers\Creator;

use App\Enums\MediaCollection;
use App\Events\NewChallengeEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConfirmNewChallengeRequest;
use App\Http\Requests\Creator\StoreChallengeRequest;
use App\Http\Requests\UpdateChallengeInformationRequest;
use App\Http\Resources\ChallengePhaseResource;
use App\Http\Resources\ChallengeResource;
use App\Http\Resources\TagResource;
use App\Models\Challenge;
use App\Models\ChallengeInvitation;
use App\Models\ChallengePhase;
use App\Notifications\ApproveChallenge;
use App\Notifications\InviteJoinChallenge;
use App\Notifications\NewChallengeNotification;
use App\Services\Interfaces\ChallengeInvitationServiceInterface;
use App\Services\Interfaces\ChallengeServiceInterface;
use App\Services\Interfaces\MediaServiceInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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
        $challenges = $this->challengeService->getChallenges();
        return $this->responseOk(ChallengeResource::collection($challenges), 'get challenges is success');
    }

    /**
     * Display a listing of the challengeTags.
     */
    public function getChallengeTags()
    {
        $challengeTags = $this->challengeService->getChallengeTags();
        return $this->responseOk(TagResource::collection($challengeTags), 'get challenge tags is success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChallengeRequest $request, MediaServiceInterface $mediaService)
    {
        $payload = $request->validated();

        try {
            $payload['images'] = Arr::map($payload['images'], function ($image) use ($mediaService) {
                return $mediaService->createMedia($image, MediaCollection::Challenge);
            });

            $payload['created_by'] = $request->user()->id;
            $challenge = $this->challengeService->createChallenge($payload);
            event(new NewChallengeEvent($challenge));
            $this->challengeInvitationService->createInvitation($challenge, $payload['invitation']);

            return $this->responseNoContent('your challenge created');
        } catch (\Throwable $th) {
            return $this->responseFailed($th->getMessage());
        }
    }

    /**
     * confirm a newly challenge.
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $challenge = $this->challengeService->getChallengeById($id);
        if (!$challenge) abort(404, 'not founded this challenge');

        $template = $this->challengeService->getChallengeTemplateById($id);
        $response = ['information' => (new ChallengeResource($challenge)), 'template' => ChallengePhaseResource::collection($template)];
        return $this->responseOk($response, 'get challenge is success');
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateBasicInformation($id, UpdateChallengeInformationRequest $request,  MediaServiceInterface $mediaService)
    {
        $payload = $request->validated();

        try {
            $payload['images'] = Arr::map($payload['images'], function ($image) use ($mediaService) {
                return $mediaService->updateMedia($image, MediaCollection::Challenge);
            });
            $this->challengeService->updateChallengeInformation($id, $payload);

            return $this->responseNoContent('basic information of your challenge was updated');
        } catch (\Throwable $th) {
            return $this->responseFailed($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->challengeService->deleteChallenge($id);
        return $this->responseNoContent('challenge deleted');
    }
}
