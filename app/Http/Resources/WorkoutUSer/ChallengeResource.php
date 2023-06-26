<?php

namespace App\Http\Resources\WorkoutUSer;

use App\Http\Resources\MediaResource;
use App\Http\Resources\TagResource;
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
            'tags' => $this->whenLoaded('tags', TagResource::collection($this->tags)),
            'phases_count' => $this->phases_count,
            'total_sessions' => $this->whenNotNull($this->total_sessions),
            'image' => new MediaResource($this->whenLoaded('image')),
            'description' => $this->description,
            'sort_desc' => $this->sort_desc,
            'created_by' => $this->whenLoaded('createdBy', $this->createdBy->name),
            'max_members' => $this->whenHas('max_members'),
            'status' => $this->status->name,
            // 'level' => $this->whenNotNull($this->level),
            'level' => $this->level->name,
            'start_at' => $this->whenNotNull($this->start_at),
            'finish_at' => $this->whenNotNull($this->finish_at),
        ];
    }
}
