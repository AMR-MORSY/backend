<?php

namespace App\Policies;

use App\Models\Instrument;
use App\Models\Users\User;
use Illuminate\Auth\Access\Response;

class InstrumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read_Instrument_data');
    }

    /**
     * Determine whether the user can view the model.
     */
    // public function view(User $user, Instrument $instrument): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_Instrument_data');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->hasPermissionTo('update_Instrument_data');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('delete_Instrument_data');
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Instrument $instrument): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, Instrument $instrument): bool
    // {
    //     //
    // }
}
