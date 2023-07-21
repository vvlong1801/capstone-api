<?php

namespace App\Http\Resources\Admin;

use App\Enums\StatusCreator;
use App\Http\Resources\MediaResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreatorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->user->name,
            "avatar" => new MediaResource($this->user->avatar),
            "email" => $this->user->email,
            "phone_number" => $this->user->phone_number,
            "age" => $this->age,
            "challenges" => $this->challenges,
            "exercises" => $this->exercises,
            "members" => $this->members,
            "gender" => $this->gender?->name,
            "isPT" => $this->verified_at != null,
            "rate" => $this->rate,
            "num_rate" => $this->num_rate,
            "status" => $this->status == StatusCreator::block ? 'block' : 'active',
        ];
    }
}
