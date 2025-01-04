<?php

namespace App\Policies;

use App\Models\IP_traffic;
use App\Models\Users\User;
use Illuminate\Auth\Access\Response;

class IP_trafficPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo("read_TX_data");
    }

    /**
     * Determine whether the user can view the model.
     */
    // public function view(User $user): bool
    // {
    //     if($user->hasPermissionTo("read_TX_data"))
    //     {
    //         return true;
    //     }
    //     else{
    //         return false;
    //     }
    // }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->hasPermissionTo("create_TX_data"))
        {
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        if($user->hasPermissionTo("update_TX_data"))
        {
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        if($user->hasPermissionTo("delete_TX_data"))
        {
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, IP_traffic $iPTraffic): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, IP_traffic $iPTraffic): bool
    // {
    //     //
    // }
}
