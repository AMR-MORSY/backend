<?php

namespace App\Policies;

use App\Models\Users\User;
use App\Models\Modification;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModificationPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */

    /////////////////////////////////Cairo///////////////////////////////////////////////////////

    public function viewAllModifications(User $user)
    {
        return $user->hasRole("Modification_Admin");
    }

    /////////////////////////////////Cairo South/////////////////////////////////////////////////
    
    public function viewCairoSouthModificationsAdmin(User $user)
    {
        return $user->hasRole("Cairo_S_Mod_Admin") ;
    }

    public function viewCairoSouthModificationsUser(User $user)
    {
        return $user->hasRole("Cairo_S_Mod_User") ;
    }

    public function viewCairoSouthSiteModifications(User $user)
    {
        return $user->hasPermissionTo('read_CS_modifications');
    }

    public function createCairoSouthSiteModification(User $user)
    {
        return $user->hasPermissionTo('create_CS_modifications');
    }

    public function updateCairoSouthSiteModification(User $user)
    {
        return $user->hasPermissionTo('update_CS_modifications');
    }

    public function deleteCairoSouthSiteModification(User $user)
    {
        return $user->hasPermissionTo('delete_CS_modifications');
    }



    /////////////////////////////////Giza/////////////////////////////////////////////////

    public function viewGizaModificationsAdmin(User $user)
    {
        return $user->hasRole("Cairo_GZ_Mod_Admin") ;
    }
    public function viewGizaModificationsUser(User $user)
    {
        return $user->hasRole("Cairo_GZ_Mod_User") ;
    }


    public function viewGizaSiteModifications(User $user)
    {
        return $user->hasPermissionTo('read_GZ_modifications');
    }

    public function createGizaSiteModification(User $user)
    {
        return $user->hasPermissionTo('create_GZ_modifications');
    }

    public function updateGizaSiteModification(User $user)
    {
        return $user->hasPermissionTo('update_GZ_modifications');
    }

    public function deleteGizaSiteModification(User $user)
    {
        return $user->hasPermissionTo('delete_GZ_modifications');
    }


    /////////////////////////////////Cairo East/////////////////////////////////////////////////
    
    public function viewCairoEastModificationsAdmin(User $user)
    {
        return $user->hasRole("Cairo_E_Mod_Admin") ;
    }

    public function viewCairoEastModificationsUser(User $user)
    {
        return $user->hasRole("Cairo_E_Mod_User") ;
    }


    public function viewCairoEastSiteModifications(User $user)
    {
        return $user->hasPermissionTo('read_CE_modifications');
    }
    public function createCairoEastSiteModification(User $user)
    {
        return $user->hasPermissionTo('create_CE_modifications');
    }

    public function updateCairoEastSiteModification(User $user)
    {
        return $user->hasPermissionTo('update_CE_modifications');
    }

    public function deleteCairoEastSiteModification(User $user)
    {
        return $user->hasPermissionTo('delete_CE_modifications');
    }


    /////////////////////////////////Cairo North/////////////////////////////////////////////////

    
    public function viewCairoNorthModificationsAdmin(User $user)
    {
        return $user->hasRole("Cairo_N_Mod_Admin") ;
    }
    public function viewCairoNorthModificationsUser(User $user)
    {
        return $user->hasRole("Cairo_N_Mod_User") ;
    }



    public function viewCairoNorthSiteModifications(User $user)
    {
        return $user->hasPermissionTo('read_CN_modifications');
    }

    public function createCairoNorthModification(User $user)
    {
        return $user->hasPermissionTo('create_CN_modifications');
    }


    public function updateCairoNorthSiteModification(User $user)
    {
        return $user->hasPermissionTo('update_CN_modifications');
    }

    public function deleteCairoNorthSiteModification(User $user)
    {
        return $user->hasPermissionTo('delete_CN_modifications');
    }


    /////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Determine whether the user can view the model.
     */
    // public function view(User $user, Modification $modification): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can create models.
    //  */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo("create_Modification_data");
    }

    // /**
    //  * Determine whether the user can update the model.
    //  */
    public function update(User $user): bool
    {
        return $user->hasPermissionTo("update_Modification_data");
    }

    // /**
    //  * Determine whether the user can delete the model.
    //  */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo("delete_Modification_data");
    }

    // /**
    //  * Determine whether the user can restore the model.
    //  */
    // public function restore(User $user, Modification $modification): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, Modification $modification): bool
    // {
    //     //
    // }
}
