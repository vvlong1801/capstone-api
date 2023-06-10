<?php

namespace App\Http\Resources;

use App\Services\Interfaces\MediaServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $url = app(MediaServiceInterface::class)->getUrl($this->disk, $this->path);

        return [
            'filename' => $this->name,
            'path' => $this->path,
            'url' => $url,
        ];
    }
}
