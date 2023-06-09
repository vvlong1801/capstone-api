<?php

namespace App\Services\Interfaces;

use App\Enums\Role;

interface UserServiceInterface
{
    public function createUserWithRole(array $data, Role $role);
}
