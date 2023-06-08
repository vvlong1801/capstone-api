<?php

namespace App\Supports;

use Illuminate\Support\Facades\Storage;

class Helper
{
    public static function randArray($array)
    {
        return $array[array_rand($array, 1)];
    }

    public static function getTemporaryUrlFromS3($filename)
    {
        return $filename !== null ? Storage::disk('s3')->temporaryUrl($filename, now()->addDays(1)) : '';
    }
}
