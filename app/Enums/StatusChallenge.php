<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum StatusChallenge: int
{
    use Helper;

    case init = 0;
    case waiting = 1;
    case doing = 2;
    case finished = 3;
    case pending = 4;
}
