<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum RoleMemberChallenge: int
{
    use Helper;

    case Member = 1;
    case MemberCensorship = 10;
    case ResultCensorship = 100;
    case Admin = 101;
}
