<?php

namespace App\Models\Traits;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasPermissions
{
    public function hasAdminPermissions(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->belongsRoles([Role::admin, Role::superAdmin])
        );
    }

    public function hasCreatorPermissions(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->belongsRoles([Role::admin, Role::superAdmin, Role::creator])
        );
    }
}
