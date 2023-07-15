<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum TypeWorkCreator: int
{
    use Helper;

    case freelancer = 1;
    case GYM = 2;
    case All = 3;
}
