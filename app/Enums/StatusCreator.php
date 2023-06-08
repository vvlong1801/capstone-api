<?php

namespace App\Enums;

enum StatusCreator: int
{
    case waitingApprove = 1;
    case approved = 2;
}
