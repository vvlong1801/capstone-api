<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeInvitationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->user_id,
            'email' => $this->user->email,
            'phone_number' => $this->user->phone_number,
            'name' => $this->user->name,
            'role' => $this->role->name,
            'expires_at' => \Carbon\Carbon::parse($this->expires_at)->format('d/m/Y'),
        ];
    }
}
