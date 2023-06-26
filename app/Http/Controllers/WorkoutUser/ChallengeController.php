<?php

namespace App\Http\Controllers\WorkoutUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChallengeResource;
use App\Services\Interfaces\ChallengeServiceInterface;
use Illuminate\Http\Request;

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
        // dd($challenges);
        return $this->responseOk(ChallengeResource::collection($challenges), 'get challenges is success');
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
