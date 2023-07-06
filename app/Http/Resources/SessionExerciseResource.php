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
            'name' => $this->exercise->name,
            'image' => new MediaResource($this->exercise->image),
            // 'data' => $this->whenLoaded('exercise', new ExerciseResource($this->exercise)),
            'exercise_id' => $this->exercise_id,
            'order' => $this->order,
            'requirement' => $this->requirement,
            'requirement_unit' => $this->requirement_unit
        ];
    }
}
