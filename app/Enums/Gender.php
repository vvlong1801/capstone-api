<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum Gender: int
{
    use Helper;
    case male = 1;
    case female = 2;
    case all = 3;
}
