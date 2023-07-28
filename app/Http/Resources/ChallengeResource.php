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
            'tags' => $this->whenLoaded('tags', TagResource::collection($this->tags)),
            'phases_count' => $this->phases_count,
            'total_sessions' => $this->whenNotNull($this->total_sessions),
            'main_image' => new MediaResource($this->whenLoaded('mainImage')),
            'description' => $this->description,
            'sort_desc' => $this->sort_desc,
            'created_by' => $this->whenLoaded('createdBy', $this->createdBy->name),
            'max_members' => $this->whenHas('max_members'),
            'status' => $this->status->name,
            'public' => $this->public ? true : false,
            // 'level' => $this->whenNotNull($this->level),
            'level' => $this->level?->name,
            'for_gender' => $this->for_gender?->name,
            'start_at' => $this->whenNotNull($this->start_at),
            'finish_at' => $this->whenNotNull($this->finish_at),
            'youtube_url' => $this->whenNotNull($this->youtube_url),
            'accept_all' => $this->accept_all ? true : false,
            // 'phases' => ChallengePhaseResource::collection($this->whenLoaded('phases')),
            'images' => MediaResource::collection($this->whenLoaded('images')),
            'members_count' => $this->whenNotNull($this->members_count),
            'rate' => $this->rateValue,
            'num_rate' => $this->numRate,
            'comments' => MessageResource::collection($this->comments),
        ];
    }
}
