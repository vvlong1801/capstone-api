<?php

namespace App\Enums;

enum Role: int
{
    case workoutUser = 1;
    case creator = 2;
    case admin = 100;
    case superAdmin = 101;
}
