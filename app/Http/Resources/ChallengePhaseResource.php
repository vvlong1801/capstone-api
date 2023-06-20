<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChallengePhaseResource extends JsonResource
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
            'order' => $this->order,
            'level' => $this->level,
            'count_sessions' => $this->count_sessions,
            'active_days' => $this->active_days,
            'rest_days' => $this->rest_days,
            'sessions' => WorkoutSessionResource::collection($this->whenLoaded('sessions')),
        ];
    }
}
