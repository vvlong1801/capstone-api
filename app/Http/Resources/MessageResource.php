<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'content' => $this->content,
            'sender' => new UserResource($this->sender),
            'receiver' => new UserResource($this->receiver),
            'replies' => MessageResource::collection($this->whenLoaded('replies')),
            'read_at' => $this->whenNotNull($this->read_at),
        ];
    }
}
