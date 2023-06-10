<?php

namespace App\Services\Interfaces;

use App\Enums\MediaCollection;
use Illuminate\Http\File;

interface MediaServiceInterface
{
    public function getUrl(string $disk, string $path);
    public function upload($file);
    public function createMedia($data, MediaCollection $collection);
    public function updateMedia($data, MediaCollection $collection);
}
