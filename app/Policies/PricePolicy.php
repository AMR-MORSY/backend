<?php

namespace App\Policies;

use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PricePolicy
{
    use HandlesAuthorization;
    /**
     * Create a new policy instance.
     */
   public function viewPriceListItems(User $user)
   {
        return $user->hasAnyDirectPermission(['read_CS_modifications','read_CE_modifications','read_GZ_modifications','read_CN_modifications']);


   }
}
