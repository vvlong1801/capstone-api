<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum Level: int
{
    use Helper;

    case easy = 1;
    case middle = 2;
    case hard = 3;
}
