<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum LevelWorkoutUser: int
{
    use Helper;

    case beginner = 1;
    case intermediate = 2;
    case advanced = 3;
}
