<?php

namespace App\Enums;

enum StatusCreator: int
{
    case none = 1;
    case request = 2;
    case block = 3;
}
