<?php

namespace App\Policies;

use App\Models\Site;
use App\Models\Users\User;
use Illuminate\Auth\Access\Response;

class SitePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read_Site_data');
    }

    /**
     * Determine whether the user can view the model.
     */
    // public function view(User $user, Site $site): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('insert_New_site');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->hasPermissionTo('update_Site_data');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('delete_Site_data');
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Site $site): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, Site $site): bool
    // {
    //     //
    // }
}
