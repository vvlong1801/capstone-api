<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum TypeMedia: int
{
    use Helper;
    case Test = 0;
    case Image = 1;
    case Icon = 2;
    case Gif = 3;
    case Video = 4;
    case Music = 5;

    public static function getType($extension)
    {
        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            return self::Image;
        }

        if ($extension == 'gif') {
            return self::Gif;
        }

        throw new \Exception("extension error", 1);
    }
}
