<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum TypeExercise: int
{
    use Helper;

    case media = 1;
}
