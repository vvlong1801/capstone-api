<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'session_order' => $this->whenLoaded('phaseSession', $this->phaseSession->order),
            'workout_day' => \Carbon\Carbon::parse($this->created_at)->format('d-m-Y'),
            'duration' => $this->duration,
            'calo' => $this->calories_burned,
            'feedback' => count($this->feedbacks) > 0 ? true : false,
            'heart_rate' => $this->heart_rate ?? 0,
        ];
    }
}
