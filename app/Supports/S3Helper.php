<?php

namespace App\Supports;

use Illuminate\Support\Facades\Storage;

class S3Helper
{
    public static function getTemporaryUrlFromS3($filename)
    {
        return $filename !== null ? Storage::disk('s3')->temporaryUrl($filename, now()->addDays(1)) : '';
    }

    public static function moveTempToLegalBucket($filename)
    {
        $disk = Storage::disk('s3-tmp');
        $s3Client = $disk->getClient();

        $s3Client->copyObject([
            'Bucket' => env('AWS_BUCKET'),
            'CopySource' => urlencode(env('AWS_BUCKET_TMP') . '/' . $filename),
            'Key' => $filename,
        ]);
    }
}
