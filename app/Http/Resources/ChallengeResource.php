<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'image' => new MediaResource($this->whenLoaded('image')),
            'description' => $this->description,
            'created_by' => $this->whenLoaded('createdBy', $this->createdBy->name),
            'max_member' => $this->max_member,
            'commit_point' => $this->commit_point,
            'status' => $this->status,
            'released_at' => $this->released_at,
            'phases' => ChallengePhaseResource::collection($this->whenLoaded('phases')),
        ];
    }
}
