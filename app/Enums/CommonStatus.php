<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum CommonStatus: int
{
    use Helper;

    case active = 1;
    case comming = 2;
    case pending = 3;
}
