<?php

namespace App\Policies;

use App\Models\Exercise;
use App\Models\User;

class ExercisePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Exercise $exercise): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasCreatorPermissions;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Exercise $exercise): bool
    {
        return $user->id === $exercise->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, $id): bool
    {
        $exercise = Exercise::findOrFail($id);
        return $user->id === $exercise->created_by || $user->hasAdminPermissions;
    }

    public function bulkDelete(User $user, $ids): bool
    {
        $exercises = Exercise::whereIn('id', $ids)->get();
        foreach ($exercises as $exercise) {
            if ($exercise && $user->can('delete', $exercise)) {
                continue;
            }
            return false;
        }
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Exercise $exercise): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Exercise $exercise): bool
    {
        //
    }
}
