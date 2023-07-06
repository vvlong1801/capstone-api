<?php

namespace App\Http\Resources;

use App\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'joined_date' => $this->joined_date,
            'joined_status' => $this->joined_status,
            'challenge_days' => $this->challenge_days,
            'challenge_started_date' => $this->challenge_started_date,
            'challenge_joined_days' => 1,
        ];
    }
}
