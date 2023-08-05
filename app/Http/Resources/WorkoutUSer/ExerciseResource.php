<?php

namespace App\Http\Resources\WorkoutUser;

use App\Http\Resources\EquipmentResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\MuscleResource;
use App\Http\Resources\TagResource;
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
            "for_gender" => $this->for_gender->name,
            'created_by' => $this->whenLoaded('createdBy', $this->createdBy->name),
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
                $this->whenNotNull($this->whenLoaded('gif'))
            ),
            "image" => new MediaResource(
                $this->whenLoaded('image')
            ),
            "youtube_url" => $this->whenNotNull($this->youtube_url),

        ];
    }
}
