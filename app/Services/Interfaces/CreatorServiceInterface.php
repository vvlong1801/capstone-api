<?php

namespace App\Services\Interfaces;

use App\Enums\Role;

interface CreatorServiceInterface
{
    public function authenticate(array $data, Role $role);
    public function verifyEmail($id, $hash);
}
