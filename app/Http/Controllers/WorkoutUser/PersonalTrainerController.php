<?php

namespace App\Http\Controllers\WorkoutUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PersonalTrainerResource;
use App\Models\Creator;
use Illuminate\Http\Request;

class PersonalTrainerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pts = Creator::with(['user', 'workoutTrainingMedia', 'certificateIssuer', 'techniques'])->whereNotNull('verified_at')->get();
        return $this->responseOk(PersonalTrainerResource::collection($pts), 'get pt success');
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
