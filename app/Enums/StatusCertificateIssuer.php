<?php

namespace App\Enums;

enum StatusCertificateIssuer: int
{
    case locked = 0;
    case active = 1;
}
