<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum RankWorkoutUser: int
{
    use Helper;

    case beginner = 0;
    case middle = 1;
    case senior = 2;
    case professional = 3;
}
