<?php

namespace App\Enums;

use App\Enums\Traits\Helper;

enum MediaCollection: string
{
    use Helper;
    case Challenge = 'challenges';
    case Avatar = 'avatars';
    case Exercise = 'exercises';
    case Muscle = 'muscles';
    case Equipment = 'equipments';
    case SessionResult = 'session_result';
    case PersonalTrainerCertificate = 'pt_certificate';
    case TrainingWorkout = 'training_workouts';
    case ExampleCertificate = 'example_certificate';
    case Temporary = 'temporaries';
}
