<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum RoleChallenge: int
{
    use Helper;

    case member = 1;
    case subadmin = 2;
}
