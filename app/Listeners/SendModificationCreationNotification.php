<?php

namespace App\Listeners;

use App\Models\Users\User;
use App\Events\ModificationCreated;
use Illuminate\Support\Facades\Config;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ModificationCreatedNotification;

class SendModificationCreationNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ModificationCreated $event): void
    {
        $modification=$event->modification;
        $modificationZone=$modification->oz;
        $action_owner=$modification->user->name;
         $subcontractor=$modification->subcontract->name;
        $proj=$modification->proj->name;
        $site_name=$modification->site->site_name;
        $site_code=$modification->site->site_code;

        $frontendUrl = Config::get('app.frontend_url');
        $data["message"] = "A new modification has been created by $action_owner in $modificationZone for $proj through $subcontractor on site $site_code $site_name  .";
        $data["title"] = "New Modification Created";
        $data["slug"] = "A new modification has been created in $modificationZone";
        $data["link"] = "$frontendUrl/modification/view/$modification->id";


       
        if($modificationZone=="Cairo East")
        {
            $users=User::role(['Modification_Admin','Cairo_E_Mod_Admin'])->get();

            Notification::send($users,new ModificationCreatedNotification($data));
        


        }
       elseif($modificationZone=="Cairo North")
        {
            $users=User::role(['Modification_Admin','Cairo_N_Mod_Admin'])->get();

            Notification::send($users,new ModificationCreatedNotification($data));
        


        }
        elseif($modificationZone=="Cairo South")
        {
            $users=User::role(['Modification_Admin','Cairo_S_Mod_Admin'])->get();

            Notification::send($users,new ModificationCreatedNotification($data));
        


        }
        elseif($modificationZone=="Giza")
        {
            $users=User::role(['Modification_Admin','Cairo_GZ_Mod_Admin'])->get();

            Notification::send($users,new ModificationCreatedNotification($data));
        


        }
       
        
    }
}
