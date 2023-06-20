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

            "evaluate_method" => $this->evaluate_method,
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
