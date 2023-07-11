<?php

namespace App\Http\Controllers\WorkoutUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkoutUser\StoreProfileRequest;
use App\Http\Resources\GoalResource;
use App\Http\Resources\WorkoutUser\ProfileResource;
use App\Services\Interfaces\ProfileServiceInterface;
use Illuminate\Http\Request;

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
    public function getGoals()
    {
        $goals = $this->profileService->getGoals();

        return $this->responseOk(GoalResource::collection($goals), 'get goals success');
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
    public function update(StoreProfileRequest $request, string $id)
    {
        $payload = $request->validated();
        try {
            $profile = $this->profileService->updateProfile($id, $payload);
            return $this->responseOk(new ProfileResource($profile), 'update profile success');
        } catch (\Throwable $th) {
            return $this->responseFailed('update failed');
        }
    }
}
