<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkoutSessionResource extends JsonResource
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
            'exercises' => SessionExerciseResource::collection($this->whenLoaded('sessionExercises')),
            'order' => $this->order,
            'name' => $this->name,
        ];
    }
}
