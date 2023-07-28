<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeMemberResource extends JsonResource
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
            'avatar' => new MediaResource($this->avatar),
            'email' => $this->email,
            'age' => $this->workoutUser->age,
            'gender' => $this->workoutUser->gender->name,
            'join_at' => $this->pivot_created_at,
        ];
    }
}
