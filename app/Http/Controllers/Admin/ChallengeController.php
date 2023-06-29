<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConfirmChallengeRequest;
use App\Models\Challenge;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function confirmChallenge($id, ConfirmChallengeRequest $request)
    {
        // change status init -> waiting/running
        $payload = $request->validated();

        if ($payload['approve']) {
            $challenge = Challenge::find($id);
            // start_at > now -> status = waiting
            // finish_at > now > start_at -> status = running
            // now > finish -> status = cancel
        }
        // send notify result to creator
        // send mail if approve = false
        // send invitation to user
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
