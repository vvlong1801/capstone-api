<?php

namespace App\Enums;

enum StatusAccount: int
{
    case not_verified = 1;
    case verified = 2;
    case locked = 3;
}
