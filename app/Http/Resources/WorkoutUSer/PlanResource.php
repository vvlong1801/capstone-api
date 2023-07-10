<?php

namespace App\Http\Resources\WorkoutUser;

use App\Http\Resources\ChallengeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'challenge' => new ChallengeResource($this->challenge),
            'current_session' => $this->current_session,
            'current_phase' => $this->current_session,
            'completed' => ($this->completed_at !== null),
        ];
    }
}
