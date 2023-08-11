<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\WorkoutUserResource;
use App\Models\User;
use App\Models\WorkoutUser;
use Illuminate\Http\Request;

class WorkoutUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workoutUsers = WorkoutUser::with(["user", "user.avatar"])->get();

        return $this->responseOk(WorkoutUserResource::collection($workoutUsers));
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
