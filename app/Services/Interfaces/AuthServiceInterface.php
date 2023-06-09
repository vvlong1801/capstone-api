<?php

namespace App\Services\Interfaces;

use App\Enums\Role;

interface AuthServiceInterface
{
    public function authenticate(array $data, Role $role);
    public function verifyEmail($id, $hash);
}
