<?php

namespace App\Http\Resources\Creator;

use App\Enums\Gender;
use App\Enums\Level;
use App\Enums\LevelWorkoutUser;
use App\Enums\StatusChallengeMember;
use App\Http\Resources\MediaResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeMemberResource extends JsonResource
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
            'name' => $this->user_name,
            'email' => $this->email,
            'challenge' => $this->challenge_name,
            'status' => StatusChallengeMember::fromValue($this->status),
            'gender' => Gender::fromValue($this->gender),
            'level' => LevelWorkoutUser::fromValue($this->level),
            'created_at' => \Carbon\Carbon::parse($this->created_at)->format('d-m-Y'),
            'avatar' => new MediaResource(User::find($this->user_id)->avatar),
        ];
    }
}
