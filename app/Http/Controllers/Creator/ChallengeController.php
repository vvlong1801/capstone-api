<?php

namespace App\Http\Controllers\Creator;

use App\Enums\MediaCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConfirmNewChallengeRequest;
use App\Http\Requests\Creator\StoreChallengeRequest;
use App\Http\Resources\ChallengeResource;
use App\Http\Resources\TagResource;
use App\Notifications\NewChallengeNotification;
use App\Services\Interfaces\ChallengeServiceInterface;
use App\Services\Interfaces\MediaServiceInterface;

class ChallengeController extends Controller
{
    protected $challengeService;

    public function __construct(ChallengeServiceInterface $challengeService)
    {
        $this->challengeService = $challengeService;
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
        $image = \Arr::get($payload, 'image', null);
        try {
            $payload['image'] = $mediaService->createMedia($image, MediaCollection::Challenge);
            $payload['created_by'] = $request->user()->id;

            $this->challengeService->createChallenge($payload);

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
            $this->challengeService->confirmNewChallenge($id, $payload);
        } catch (\Throwable $th) {
            //throw $th;
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

    /**
     * Update the specified resource in storage.
     */
    public function update($id, StoreChallengeRequest $request,  MediaServiceInterface $mediaService)
    {
        $payload = $request->validated();
        $image = \Arr::get($payload, 'image', false);
        $payload['image'] = $mediaService->updateMedia($image, MediaCollection::Challenge);
        $challenge = $this->challengeService->updateChallenge($id, $payload);

        return $this->getResponse(new ChallengeResource($challenge), 'challenge updated');
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
