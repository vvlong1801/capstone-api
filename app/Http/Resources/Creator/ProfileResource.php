<?php

namespace App\Http\Resources\Creator;

use App\Http\Resources\CertificateIssuerResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "user" => new UserResource($this->user),
            "age" => $this->age,
            "youtube" => $this->youtube,
            "address" => $this->address,
            "facebook" => $this->facebook,
            "workout_training_media" =>  MediaResource::collection($this->whenLoaded('workoutTrainingMedia') ?? []),
            "certificate" => new MediaResource($this->whenLoaded('certificate')),
            "certificate_issuer" => new CertificateIssuerResource($this->certificateIssuer),
            "work_type" => $this->work_type?->name,
            "techniques" => TagResource::collection($this->whenLoaded('techniques')),
            "desired_salary" => $this->desired_salary,
            "introduce" => $this->introduce,
            "gender" => $this->gender?->name,
            "zalo" => $this->zalo,
        ];
    }
}
