<?php

namespace App\Policies;

use App\Models\NUR2G;
use App\Models\Users\User;
use Illuminate\Auth\Access\Response;

class NUR2GPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read_NUR_data');
    }

    /**
     * Determine whether the user can view the model.
     */
    // public function view(User $user, NUR2G $nUR2G): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_NUR_data');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->hasPermissionTo('update_NUR_data');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('delete_NUR_data');
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, NUR2G $nUR2G): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, NUR2G $nUR2G): bool
    // {
    //     //
    // }
}
