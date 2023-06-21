<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum Role: int
{
    use Helper;

    case workoutUser = 1;
    case creator = 2;
    case admin = 100;
    case superAdmin = 101;
}
