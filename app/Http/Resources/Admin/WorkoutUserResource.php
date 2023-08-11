<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkoutUserResource extends JsonResource
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
            "name" => $this->user->name,
            "email" => $this->user->email,
            "phone_number" => $this->user->phone_number,
            "avatar" => $this->user->avatar,
            "gender" => $this->gender->name,
            "age" => $this->age,
            "height" => $this->height,
            "weight" => $this->weight,
            "bmi" => $this->bmi,
            "level" => $this->level->name,
        ];
    }
}
