<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateIssuerResource extends JsonResource
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
            'description' => $this->description,
            'example' => new MediaResource($this->whenLoaded('exampleCertificate')),
            'status' => $this->status->name,
            'created_at' => \Carbon\Carbon::parse($this->created_at)->format("d/m/Y"),
        ];
    }
}
