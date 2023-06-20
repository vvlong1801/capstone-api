<?php

namespace App\Services\MediaServices;

use App\Enums\CommonStatus;
use App\Enums\MediaCollection;
use App\Enums\TypeMedia;
use App\Models\Media;
use App\Services\BaseService;
use App\Services\Interfaces\MediaServiceInterface;
use Exception;
use Illuminate\Filesystem\AwsS3V3Adapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class S3Service extends BaseService implements MediaServiceInterface
{
    protected AwsS3V3Adapter $tempDisk;
    protected AwsS3V3Adapter $disk;

    public function __construct()
    {
        $this->tempDisk = Storage::disk('s3-tmp');
        $this->disk = Storage::disk('s3');
    }

    public function getUrl($disk, string $path)
    {
        return $disk == 's3' ? $this->disk->temporaryUrl($path, now()->addDay()) : $this->tempDisk->temporaryUrl($path, now()->addDay());
    }

    public function upload($file)
    {
        try {
            $path =  $this->tempDisk->put("/", $file);
            return new Media([
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'mime_type' => $file->extension(),
                'collection_name' => MediaCollection::Temporary,
                'disk' => 's3-tmp',
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createMedia($data, MediaCollection $collection)
    {
        $path = $data['path'];
        $filename = $data['filename'];

        // CHECK EXISTED FILE PATH ON TMP DISK
        if ($this->tempDisk->exists($path)) {
            // IF EXISTED MOVE TO COLLECTION DISK
            $newPath =  $collection->value . '/' . uniqid() . '-' . $filename;
            $result = $this->disk->getClient()->copyObject([
                'Bucket' => env('AWS_BUCKET'),
                'CopySource' => env('AWS_BUCKET_TMP') . '/' . $path,
                'Key' => $newPath,
            ]);

            $mimeType = pathinfo($path, PATHINFO_EXTENSION);

            if ($result['@metadata']['statusCode'] == 200) {
                return new Media([
                    'name' => $filename,
                    'path' => $newPath,
                    'mime_type' => $mimeType,
                    'collection_name' => $collection,
                    'status' => CommonStatus::comming,
                    'disk' => 's3'
                ]);
            } else {
                throw new Exception("server error!!");
            }
        } else {
            throw new ValidationException("this file wasn't existed!");
        }
    }

    public function updateMedia($data, MediaCollection $collection)
    {
        $media = Media::wherePath($data['path'])->whereName($data['filename'])->first();

        // if existed media in local disk then return
        // if existed media in temp disk then create
        if ($media?->disk === 's3') {
            return;
        } else {
            return $this->createMedia($data, $collection);
        }
    }
}
