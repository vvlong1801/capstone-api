<?php

namespace App\Http\Controllers\Creator;

use App\Enums\MediaCollection;
use App\Enums\StatusChallengeMember;
use App\Events\NewChallengeEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConfirmNewChallengeRequest;
use App\Http\Requests\Creator\ConfirmNewChallengeMemberRequest;
use App\Http\Requests\Creator\StoreChallengeRequest;
use App\Http\Requests\ReplyFeedbackRequest;
use App\Http\Requests\UpdateChallengeInformationRequest;
use App\Http\Requests\UpdateChallengeInvitationRequest;
use App\Http\Resources\ChallengeInvitationResource;
use App\Http\Resources\ChallengeMemberResource;
use App\Http\Resources\ChallengePhaseResource;
use App\Http\Resources\ChallengeResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\TagResource;
use App\Models\Challenge;
use App\Models\Message;
use App\Notifications\ApproveChallenge;
use App\Services\Interfaces\ChallengeInvitationServiceInterface;
use App\Services\Interfaces\ChallengeServiceInterface;
use App\Services\Interfaces\MediaServiceInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;

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
        $user = Auth::user();
        try {
            $payload['images'] = Arr::map($payload['images'], function ($image) use ($mediaService) {
                return $mediaService->createMedia($image, MediaCollection::Challenge);
            });

            $payload['created_by'] = $user->id;
            $challenge = $this->challengeService->createChallenge($payload);
            if ($user->isCreator) {
                event(new NewChallengeEvent($challenge));
            }
            $this->challengeInvitationService->createInvitation($challenge, $payload['invitation']);

            return $this->responseNoContent('your challenge created');
        } catch (\Throwable $th) {
            return $this->responseFailed($th->getMessage());
        }
    }

    // /**
    //  * confirm a newly challenge.
    //  */
    // public function confirmNewChallenge($id, ConfirmNewChallengeRequest $request)
    // {
    //     $payload = $request->validated();

    //     try {
    //         if ($payload['approve']) {
    //             $challenge = $this->challengeService->approveChallenge($id);
    //             //notify invitation
    //             $invitations = $this->challengeInvitationService->getInvitationByChallengeId($id);
    //             // Notification::send()
    //             // foreach ($invitations as $key => $invitation) {
    //             //     Notification::send($invitation->user, new InviteJoinChallenge($invitation, true));
    //             // }
    //             //notify creator
    //             Notification::send($challenge->createdBy, new ApproveChallenge($challenge, true));
    //         } else {
    //             $challenge = Challenge::find($id);
    //             Notification::send($challenge->createdBy, new ApproveChallenge($challenge, false));
    //         }
    //         return $this->responseNoContent('confirm success');
    //     } catch (\Throwable $th) {
    //         throw $th;
    //     }
    // }


    public function confirmNewChallengeMember(ConfirmNewChallengeMemberRequest $request)
    {
        $payload = $request->validated();
        DB::table('challenge_members')->where('user_id', $payload['member_id'])
            ->where('challenge_id', $payload['challenge_id'])
            ->update(['status' => $payload['accept'] ? StatusChallengeMember::approved : StatusChallengeMember::unApproved]);
        return $this->responseNoContent('accept success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $challenge = $this->challengeService->getChallengeById($id);
        if (!$challenge) abort(404, 'not founded this challenge');

        $template = $this->challengeService->getChallengeTemplateById($id);
        $invitations = $this->challengeInvitationService->getInvitationByChallengeId($id);
        // $feedbacks = $this->challengeService->getFeedbacksByChallengeId($id);
        $response = [
            'information' => (new ChallengeResource($challenge)),
            'template' => ChallengePhaseResource::collection($template),
            'invitation' => ChallengeInvitationResource::collection($invitations),
            'members' => ChallengeMemberResource::collection($challenge->members),
            // 'feedbacks' => MessageResource::collection($feedbacks),
        ];
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

    public function updateInvitation($id, UpdateChallengeInvitationRequest $request)
    {
        try {
            $payload = $request->validated();
            $mappedPayload = \Arr::map($payload['invitations'], function ($item) {
                return [
                    'user_id' => $item['id'],
                    'role' => $item['role'],
                ];
            });

            $challenge = Challenge::find($id);

            $this->challengeInvitationService->createInvitation($challenge, $mappedPayload);

            return $this->responseNoContent('your challenge update invitation');
        } catch (\Throwable $th) {
            abort(500, $th->getMessage());
        }
    }

    public function getFeedbacks($id)
    {
        $feedbacks = $this->challengeService->getFeedbacksByChallengeId($id);
        return $this->responseOk(MessageResource::collection($feedbacks), 'success');
    }

    public function replyFeedback($challengeId, $feedbackId, ReplyFeedbackRequest $request)
    {
        $payload = $request->validated();

        Message::create([
            'messageable_type' => Challenge::class,
            'messageable_id' => $challengeId,
            'sender_id' => Auth::user()->id,
            'receiver_id' => $payload['receiver_id'],
            'reply_id' => $feedbackId,
            'content' => $payload['content'],
        ]);

        return $this->responseNoContent('reply success');
    }

    public function getComments($challengeId)
    {
        $comments =  Message::where('messageable_type', Challenge::class)->where('messageable_id', $challengeId)->orderBy('created_at', 'desc')->get();
        return $this->responseOk(MessageResource::collection($comments), 'get commments ok');
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
