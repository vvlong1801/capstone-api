<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum TypeTag: int
{
    use Helper;

    case GroupExercise = 1;
}
