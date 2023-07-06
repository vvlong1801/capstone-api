<?php

namespace App\Http\Controllers\WorkoutUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChallengePhaseResource;
use App\Http\Resources\ChallengeResource;
use App\Http\Resources\WorkoutUser\PlanResource;
use App\Services\Interfaces\ChallengeServiceInterface;
use App\Services\Interfaces\PlanServiceInterface;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    protected $planService;

    public function __construct(PlanServiceInterface $planService)
    {
        $this->planService = $planService;

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $planChallenges = $this->planService->getPlanChallenges();

        return $this->responseOk(PlanResource::collection($planChallenges), 'get plan success');
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
    public function show(string $id, ChallengeServiceInterface $challengeService)
    {
        $plan = $this->planService->getPlanById($id);
        $template = $challengeService->getChallengeTemplateById($plan->challenge_id);
        return $this->responseOk(['plan' => new PlanResource($plan), 'schedule' =>ChallengePhaseResource::collection($template)], 'get plan success');
    }

    /**
     * Display the specified resource.
     */
    public function getSchedule(string $id, ChallengeServiceInterface $challengeService)
    {
        $plan = $this->planService->getPlanById($id);
        $template = $challengeService->getChallengeTemplateById($plan->challenge_id);
        return $this->responseOk(
            ChallengePhaseResource::collection($template), 'get plan success');
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
