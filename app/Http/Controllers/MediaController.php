<?php

namespace App\Http\Controllers;

use App\Enums\MediaCollection;
use App\Http\Requests\UploadMediaRequest;
use App\Http\Resources\MediaResource;
use App\Services\Interfaces\MediaServiceInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends Controller
{
    protected $mediaService;
    public function __construct(MediaServiceInterface $mediaService)
    {
        $this->mediaService = $mediaService;
    }
    /**
     * Handle the incoming request.
     */
    public function upload(UploadMediaRequest $request)
    {
        $payload = $request->validated();
        try {
            $media = $this->mediaService->upload($payload['file']);
            return $this->responseOk(new MediaResource($media), 'upload is success');
        } catch (\Throwable $th) {
            abort(Response::HTTP_BAD_REQUEST);
        }
    }

    public function create(Request $request) {
        try {
            $media = $this->mediaService->createMedia($request->all(), MediaCollection::Exercise);

            dd($media);
        } catch (\Throwable $th) {
            throw $th;
        }

    }
}
