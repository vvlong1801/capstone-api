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
            'order' => $this->order,
            'requirement' => $this->requirement,
            'requirement_unit' => $this->requirement_unit
        ];
    }
}
