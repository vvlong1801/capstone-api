<?php

namespace App\Http\Controllers\Creator;

use App\Enums\MediaCollection;
use App\Enums\TypeTag;
use App\Events\NewPersonalTrainerEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Creator\BecamePersonalTrainerRequest;
use App\Http\Requests\Creator\StoreProfileRequest;
use App\Http\Resources\CertificateIssuerResource;
use App\Http\Resources\Creator\ProfileResource;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Services\Interfaces\MediaServiceInterface;
use App\Services\Interfaces\ProfileServiceInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileServiceInterface $profileService)
    {
        $this->profileService = $profileService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profile = $this->profileService->getProfileCreatorByUserId(Auth::user()->id);
        return $this->responseOk(new ProfileResource($profile));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function requestBecomePersonalTrainer(BecamePersonalTrainerRequest $request, MediaServiceInterface $mediaService)
    {
        $payload = $request->validated();
        try {
            if ($payload['user']['avatar']) {
                $payload['user']['avatar'] = $mediaService->updateMedia($payload['user']['avatar'], MediaCollection::Avatar);
            }

            if ($payload['certificate']) {
                $payload['certificate'] = $mediaService->updateMedia($payload['certificate'], MediaCollection::PersonalTrainerCertificate);
            }

            if (count($payload['workout_training_media'])) {
                $payload['workout_training_media'] = Arr::map($payload['workout_training_media'], function ($image) use ($mediaService) {
                    return $mediaService->updateMedia($image, MediaCollection::TrainingWorkout);
                });
            }

            $creator = $this->profileService->updatePersonalTrainerProfile(Auth::user()->id, $payload);

            event(new NewPersonalTrainerEvent($creator));
            return $this->responseNoContent('request was sended');
        } catch (\Throwable $th) {
            abort(404, $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $profile = $this->profileService->getProfileCreatorByUserId(Auth::user()->id);
        return $this->responseOk(new ProfileResource($profile));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProfileRequest $request, MediaServiceInterface $mediaService)
    {
        $payload = $request->validated();
        try {
            if ($payload['user']['avatar']) {
                $payload['user']['avatar'] = $mediaService->updateMedia($payload['user']['avatar'], MediaCollection::Avatar);
            }

            $this->profileService->updateCreatorProfile(Auth::user()->id, $payload);
            return $this->responseNoContent('profile updated');
        } catch (\Throwable $th) {
            abort(404, $th->getMessage());
        }
    }

    /**
     * Get issuers
     */
    public function getCertificateIssuers()
    {
        $issuers = $this->profileService->getCertificateIssuers();

        return $this->responseOk(CertificateIssuerResource::collection($issuers));
    }

    /**
     * Get issuers
     */
    public function getTechniques()
    {
        $techniques = Tag::where('type', TypeTag::CreatorTechnique)->get();

        return $this->responseOk(TagResource::collection($techniques));
    }
}
