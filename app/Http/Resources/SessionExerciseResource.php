<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionExerciseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'exercise' => new ExerciseResource($this->exercise),
            'order' => $this->order,
            'is_primary' => $this->is_primary,
            'requirements' => ExerciseRequirementResource::collection($this->requirements),
        ];
    }
}
