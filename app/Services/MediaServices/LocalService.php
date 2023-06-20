<?php

namespace App\Services\MediaServices;

use App\Enums\CommonStatus;
use App\Enums\MediaCollection;
use App\Models\Media;
use App\Services\BaseService;
use App\Services\Interfaces\MediaServiceInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class LocalService extends BaseService implements MediaServiceInterface
{
    protected Filesystem $tempDisk;
    protected Filesystem $disk;

    public function __construct()
    {
        $this->tempDisk = Storage::disk('public');
        $this->disk = Storage::disk('local');
    }

    public function getUrl($diskType, $path)
    {
        if ($diskType === 'local') {
            $this->disk->copy($path, 'public/' . $path);
        }
        return $this->tempDisk->url($path);
    }

    public function upload($file)
    {
        try {
            // save file to temp storage
            $path =  $this->tempDisk->put("/", $file);
            // save to database
            return new Media([
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'collection_name' => MediaCollection::Temporary,
                'mime_type' => $file->extension(),
                'disk' => 'local-tmp',
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createMedia($data, $collection)
    {
        $path = $data['path'];
        $filename = $data['filename'];
        // check existed in temp disk
        if (!$this->tempDisk->exists($path)) throw new \Exception("not found temporary file", 1);
        else {
            // copy temp disk to disk
            $newPath = $collection->value . '/' . uniqid() . '-' . $filename;
            $this->disk->copy('public/' . $path, $newPath);
            // get mime_type
            $mimeType = pathinfo($path, PATHINFO_EXTENSION);

            return new Media([
                'name' => $filename,
                'path' => $newPath,
                'mime_type' => $mimeType,
                'collection_name' => $collection,
                'status' => CommonStatus::comming,
                'disk' => 'local'
            ]);
        }
    }

    public function updateMedia($file, $collection)
    {
        // select disk of media in database
        $media = Media::wherePath($file['path'])->whereName($file['filename'])->first();
        // if existed media in local disk then return
        // if existed media in temp disk then create
        if ($media?->disk === 'local') {
            return;
        } else {
            return $this->createMedia($file, $collection);
        }
    }
}
