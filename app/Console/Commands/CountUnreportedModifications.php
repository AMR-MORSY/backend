<?php

namespace App\Console\Commands;

use App\Models\Users\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use App\Models\Modifications\Modification;
use Illuminate\Support\Facades\Notification;
use App\Models\Modifications\ModificationReport;
use App\Notifications\UnreportedModificationsNotification;

class CountUnreportedModifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:count-unreported-modifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'count no. of un reported modifications and notify the area owner';

    /**
     * Execute the console command.
     */
    private function getAreaOwners($modificationZone)
    {

        if ($modificationZone == "Cairo East") {
            $users = User::role(['Modification_Admin', 'Cairo_E_Mod_Admin'])->get();
            return $users;
        } elseif ($modificationZone == "Cairo North") {
            $users = User::role(['Modification_Admin', 'Cairo_N_Mod_Admin'])->get();
            return $users;
        } elseif ($modificationZone == "Cairo South") {
            $users = User::role(['Modification_Admin', 'Cairo_S_Mod_Admin'])->get();
            return $users;
        } elseif ($modificationZone == "Giza") {
            $users = User::role(['Modification_Admin', 'Cairo_GZ_Mod_Admin'])->get();
            return $users;
        }
    }
    public function handle()
    {
        $UnreportedModification = ModificationReport::where('name', 'No')->first();
        $UnreportedModifications = $UnreportedModification->modifications;
        $actionOwners = $UnreportedModifications->groupBy('action_owner');

        $frontendUrl = Config::get('app.frontend_url');

        foreach ($actionOwners as $key => $ownerModifications) {


            $countOfModifications = count($ownerModifications);
            if ($countOfModifications > 0) {
                $user = User::find($key); /////////////////refer to the action_owner

                $operation_zones = $ownerModifications->groupBy('oz');
                foreach ($operation_zones as $zone => $operations) {//////////////because the site engineer works in different zones so we have to get the count of modifications in every zone
                    $areaAwners = $this->getAreaOwners($zone); //// area owners and modification admin
                    $countZoneModifications=count($operations);
                    $data["message"] = "You have $countZoneModifications unreported modification work orders.Reporting a modification work order ensures transparency and accuracy in procurement. It helps verify that the final PO aligns with the initially agreed terms, pricing, and specifications. This practice prevents misunderstandings, reduces discrepancies, and provides a clear audit trail. By maintaining proper documentation, organizations enhance accountability, streamline approvals, and ensure compliance with procurement policies.";
                    $data["title"] = "Unreported Modifications";
                    $data["slug"] = "You have $countZoneModifications unreported modification work orders in $zone created by $user->name";
                    $data["link"] = "$frontendUrl/modifications/unreported-modifications/$zone/$user->id";///////this link will returns to  area owners and modification admin the modification of that specific action owner

                    Notification::send($areaAwners, new UnreportedModificationsNotification($data));/////////////////sending notification to modification admin and area owner
                }
              
                $data["message"] = "You have $countOfModifications unreported modification work orders.Reporting a modification work order ensures transparency and accuracy in procurement. It helps verify that the final PO aligns with the initially agreed terms, pricing, and specifications. This practice prevents misunderstandings, reduces discrepancies, and provides a clear audit trail. By maintaining proper documentation, organizations enhance accountability, streamline approvals, and ensure compliance with procurement policies.";
                $data["title"] = "Unreported Modifications";
                $data["slug"] = "You have $countOfModifications unreported modification work orders";
                $data["link"] = "$frontendUrl/modifications/unreported-modifications";
                $user->notify(new UnreportedModificationsNotification($data));////////////////////sending notification to site engineer with all unreported modifications regardless the zone
            }
        }
        return 0;
    }
}
