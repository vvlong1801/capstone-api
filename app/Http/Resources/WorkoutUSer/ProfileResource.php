<?php

namespace App\Http\Resources\WorkoutUser;

use App\Http\Resources\GoalResource;
use App\Http\Resources\MediaResource;
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
            'id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'avatar' => new MediaResource($this->user->avatar),
            'phone_number' => $this->whenNotNull($this->user->phone_number),
            'gender' => $this->whenNotNull($this->gender?->name),
            'age' => $this->whenNotNull($this->age),
            'height' => $this->whenNotNull($this->height),
            'weight' => $this->whenNotNull($this->weight),
            'level' => $this->whenNotNull($this->level?->name),
            'goals' => GoalResource::collection($this->whenLoaded('user.goals', $this->user->goals)),
            'first_login' => $this->user->first_login,
        ];
    }
}
