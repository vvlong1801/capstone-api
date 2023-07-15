<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum TypeTag: int
{
    use Helper;

    case GroupExercise = 1;
    case ChallengeTag = 2;
    case CreatorTechnique = 3;
    case GoalTag = 4;
}
