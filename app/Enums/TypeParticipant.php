<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum TypeParticipant: int
{
    use Helper;

    case All = 1;
    case Group = 2;
    case Assign = 3;
}
