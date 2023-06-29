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
            'level' => $this->whenNotNull($this->level),
            'total_days' => $this->total_days,
            'sessions' => PhaseSessionResource::collection($this->whenLoaded('sessions')),
        ];
    }
}
