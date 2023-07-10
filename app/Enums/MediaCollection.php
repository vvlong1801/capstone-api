<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum MediaCollection: string
{
    use Helper;
    case Challenge = 'challenges';
    case Exercise = 'exercises';
    case Muscle = 'muscles';
    case Equipment = 'equipments';
    case PlanSession = 'plan_session';
    case Temporary = 'temporaries';
}
