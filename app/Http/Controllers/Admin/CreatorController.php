<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CreatorResource;
use App\Services\Interfaces\CreatorServiceInterface;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreCreatorRequest;
use Symfony\Component\HttpFoundation\Response;

class CreatorController extends Controller
{
    protected $creatorService;
    public function __construct(CreatorServiceInterface $creatorService)
    {
        $this->creatorService = $creatorService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $creators = $this->creatorService->getCreators();
        return $this->responseOk(CreatorResource::collection($creators));
    }

    /**
     * Display a listing of search.
     */
    public function search(Request $request)
    {
        $creators = $this->creatorService->getCreators($request);
        return $this->responseOk(CreatorResource::collection($creators));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $payload = [];
        $payload['user_id'] = $this->user()->id;

        try {
            $this->creatorService->createCreator($payload);
            return $this->responseNoContent('Creator was created');
        } catch (\Throwable $th) {
            abort(404, $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $creator = $this->creatorService->getCreatorById($id);
            $creator = new CreatorResource($creator);

            return $this->responseOk($creator, 'get Creators success');
        } catch (\Throwable $th) {
            abort(404, 'not found data');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $payload = [];
        $payload['user_id'] = $this->user()->id;

        try {
            $this->creatorService->updateCreator($id, $payload);
            return $this->responseNoContent('Creator was updated');
        } catch (\Throwable $th) {
            abort(404, $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->creatorService->deleteCreator($id);
            return $this->responseNoContent('Creator was deleted');
        } catch (\Throwable $th) {
            abort(Response::HTTP_BAD_REQUEST, $th->getMessage());
        }
    }
}
