<?php

namespace App\Services\Interfaces;

use App\Enums\Role;

interface UserServiceInterface
{
    public function getUsers();
    public function searchByEmailOrPhoneNumber(string $payloads);
    public function createUserWithRole(array $data, Role $role);
}
