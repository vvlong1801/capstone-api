<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MediaCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEquipmentRequest;
use App\Http\Resources\EquipmentResource;
use App\Services\Interfaces\EquipmentServiceInterface;
use App\Services\Interfaces\MediaServiceInterface;

class EquipmentController extends Controller
{
    protected $equipmentService;
    public function __construct(EquipmentServiceInterface $equipmentService)
    {
        $this->equipmentService = $equipmentService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipments = $this->equipmentService->getEquipments();
        return $this->responseOk(EquipmentResource::collection($equipments), 'get equipments is success!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEquipmentRequest $request, MediaServiceInterface $mediaService)
    {
        $payload = $request->validated();
        $icon = \Arr::get($payload, 'icon', false);
        try {
            $payload['image'] = $mediaService->createMedia($payload['image'], MediaCollection::Equipment);
            $payload['icon'] = $icon ? $mediaService->createMedia($icon, MediaCollection::Equipment) : null;
            $this->equipmentService->createEquipment($payload);
            return $this->responseNoContent('create equipment is success!');
        } catch (\Throwable $th) {
            abort(400, $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreEquipmentRequest $request, string $id, MediaServiceInterface $mediaService)
    {
        $payload = $request->validated();
        $icon = \Arr::get($payload, 'icon', false);
        try {
            $payload['image'] = $mediaService->updateMedia($payload['image'], MediaCollection::Equipment);
            $payload['icon'] = $icon ? $mediaService->updateMedia($icon, MediaCollection::Equipment) : null;
            $this->equipmentService->updateEquipment($id, $payload);
            return $this->responseNoContent('update equipment is success!');
        } catch (\Throwable $th) {
            abort(400, $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->equipmentService->deleteEquipment($id);

        return $this->responseNoContent('delete equipment is success!');
    }
}
