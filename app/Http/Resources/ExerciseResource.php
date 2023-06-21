<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "level" => $this->level,
            "type" => $this->type,
            "created_by" =>  $this->createdBy->name,
            "group_tags" => TagResource::collection($this->groupTags),

            "requirement_unit" => $this->requirement_unit,
            "requirement_initial" => $this->requirement_initial,
            "equipment" => new EquipmentResource(
                $this->whenLoaded('equipment')
            ),
            "muscles" => MuscleResource::collection(
                $this->whenLoaded('muscles')
            ),
            "description" => $this->description,
            "gif" => new MediaResource(
                $this->whenLoaded('gif')
            ),
            "image" => new MediaResource(
                $this->whenLoaded('image')
            ),
            "video" => new MediaResource(
                $this->whenLoaded('video')
            ),

        ];
    }
}
