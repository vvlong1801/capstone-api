<?php

namespace App\Http\Controllers\WorkoutUser;

use App\Enums\MediaCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkoutUser\StoreResultWorkoutRequest;
use App\Notifications\NotifyMemberCompletedSession;
use App\Services\Interfaces\MediaServiceInterface;
use App\Services\Interfaces\WorkoutServiceInterface;
use Illuminate\Support\Facades\Notification;

class WorkoutController extends Controller
{

    protected $workoutService;

    public function __construct(WorkoutServiceInterface $workoutService)
    {
        $this->workoutService = $workoutService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeResult(StoreResultWorkoutRequest $request, MediaServiceInterface $mediaService)
    {
        $payload = $request->validated();
        if ($payload['video']) {
            $payload['video'] = \Arr::map($payload['video'], function ($video) use ($mediaService) {
                return $mediaService->createMedia($video, MediaCollection::PlanSession);
            });
        }
        try {
            $resultWorkout = $this->workoutService->saveResultWorkoutSession($payload);

            if ($payload['notify_creator']) {
                Notification::send($resultWorkout->plan->user, new NotifyMemberCompletedSession($resultWorkout));
            }
            return $this->responseNoContent("save result done");
        } catch (\Throwable $th) {
            return $this->responseFailed('create result fail');
        }
    }

}
