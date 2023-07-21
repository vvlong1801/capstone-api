<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusCreator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VerifiedPersonalTrainerRequest;
use App\Http\Resources\Admin\CreatorResource;
use App\Http\Resources\Admin\PersonalTrainerRequestResource;
use App\Http\Resources\Admin\PersonalTrainerResource;
use App\Models\Creator;
use App\Notifications\ApprovePersonalTrainer;
use App\Notifications\UnApprovePersonalTrainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class CreatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $creators = Creator::with('user')->where('verified_at', null)->get();
        return $this->responseOk(CreatorResource::collection($creators));
    }

    public function getRequestPT()
    {
        $creators = Creator::with(['user', 'user.avatar'])->where('status', StatusCreator::request)->get();
        return $this->responseOk(PersonalTrainerRequestResource::collection($creators));
    }

    public function getPersonalTrainers()
    {
        $creators = Creator::with(['user', 'user.avatar'])->where('status', StatusCreator::none)->whereNot('verified_at', null)->get();
        return $this->responseOk(PersonalTrainerResource::collection($creators));
    }


    public function verifyPersonalTrainer($id, VerifiedPersonalTrainerRequest $request)
    {
        $payload = $request->validated();

        if ($payload['accept']) {
            $creator = Creator::find($id);
            $creator->update(['verified_at' => \Carbon\Carbon::now(), 'status' => StatusCreator::none]);
            Notification::send($creator->user, new ApprovePersonalTrainer());
        } else {
            $creator = Creator::find($id);
            $creator->update(['status' => StatusCreator::none]);
            Notification::send($creator->user, new UnApprovePersonalTrainer($payload['message'] ?? ""));
        }
        return $this->responseNoContent('verify success');
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
        return Creator::with(['user', 'user.avatar', 'techniques', 'certificate', 'workoutTrainingMedia', 'certificateIssuer'])->whereId($id)->first();
    }

    /**
     * Display the specified resource.
     */
    public function showRequest(string $id)
    {
        $creator = Creator::with(['user', 'user.avatar', 'techniques', 'certificate', 'workoutTrainingMedia', 'certificateIssuer'])->whereId($id)->first();

        return $this->responseOk(new PersonalTrainerRequestResource($creator), 'get request success');
    }

    /**
     * Display the specified resource.
     */
    public function showPersonalTrainer(string $id)
    {
        $creator = Creator::with(['user', 'user.avatar', 'techniques', 'certificate', 'workoutTrainingMedia', 'certificateIssuer'])->whereId($id)->first();

        return $this->responseOk(new PersonalTrainerResource($creator), 'get personal trainer success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Creator::destroy($id);
    }
}
