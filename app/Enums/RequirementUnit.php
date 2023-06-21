<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum RequirementUnit: string
{
    use Helper;

    case reps = "reps";
    case times = "times";
    case meters = "meters";
}
