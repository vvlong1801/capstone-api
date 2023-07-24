<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum StatusChallengeMember: int
{
    use Helper;
    case waitingApprove = 0;
    case approved = 1;
    case unApproved = 2;
}
