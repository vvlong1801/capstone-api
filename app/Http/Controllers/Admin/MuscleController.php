<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MediaCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMuscleRequest;
use App\Http\Resources\MuscleResource;
use App\Services\Interfaces\MediaServiceInterface;
use App\Services\Interfaces\MuscleServiceInterface;

class MuscleController extends Controller
{
    protected $muscleService;
    public function __construct(MuscleServiceInterface $muscleService)
    {
        $this->muscleService = $muscleService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $muscles = $this->muscleService->getMuscles();
        return $this->responseOk(MuscleResource::collection($muscles), 'get muscles is success!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMuscleRequest $request, MediaServiceInterface $mediaService)
    {
        $payload = $request->validated();
        $image = \Arr::get($payload, 'image');
        $icon = \Arr::get($payload, 'icon', false);

        try {
            $payload['image'] = $mediaService->createMedia($image, MediaCollection::Muscle);
            $payload['icon'] = $icon ? $mediaService->createMedia($icon, MediaCollection::Muscle) : null;
             $this->muscleService->createMuscle($payload);
            return $this->responseNoContent('create muscle is success!');
        } catch (\Throwable $th) {
            abort(404, $th->getMessage());
        }
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
    public function update(StoreMuscleRequest $request, string $id, MediaServiceInterface $mediaService)
    {
        $payload = $request->validated();
        $image = \Arr::get($payload, 'image');
        $icon = \Arr::get($payload, 'icon', false);
        
        try {
            $payload['image'] = $mediaService->updateMedia($image, MediaCollection::Muscle);
            $payload['icon'] = $icon ? $mediaService->updateMedia($icon, MediaCollection::Muscle) : null;

            $this->muscleService->updateMuscle($id, $payload);
            return $this->responseNoContent('update muscle is success!');
        } catch (\Throwable $th) {
            abort(400, $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->muscleService->deleteMuscle($id);

        return $this->responseNoContent('delete muscle is success!');
    }
}
