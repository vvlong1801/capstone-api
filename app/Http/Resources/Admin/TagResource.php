<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return             [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type->name,
            'created_by' => $this->whenLoaded('createdBy', $this->createdBy->name),
            'created_at' => \Carbon\Carbon::parse($this->created_at)->format('d/m/Y'),
            'weight' => $this->whenNotNull($this->pivot?->weight)
        ];
    }
}
