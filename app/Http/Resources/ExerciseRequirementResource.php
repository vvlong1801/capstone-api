<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseRequirementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'param' => $this->param,
            'param_type' => $this->param_type,
            'value' => $this->value,
            'unit' => $this->unit,
            'order' => $this->order,
        ];
    }
}
