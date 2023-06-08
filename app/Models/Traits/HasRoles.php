<?php

namespace App\Models\Traits;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasRoles
{
    /**
     * check is role
     *
     * @param int $role
     * @return Attribute
     */
    private function isRole($role)
    {
        // dd($this->account->role == $role);
        return $this->account->role == $role;
    }

    /**
     * check current role user belongs to roles
     *
     * @param array $roles
     * @return Attribute
     */
    public function belongsRoles(array $roles)
    {
        return in_array($this->account->role, $roles);
    }

    /**
     * check logged user is member
     *
     * @return Attribute
     */
    public function isWorkoutUser(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->isRole(Role::workoutUser)
        );
    }

    /**
     * check user is admin
     *
     * @return Attribute
     */
    public function isAdmin(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->isRole(Role::admin)
        );
    }

    /**
     * check user is super admin
     *
     * @return Attribute
     */
    public function isSuperAdmin(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->isRole(Role::superAdmin)
        );
    }
}
