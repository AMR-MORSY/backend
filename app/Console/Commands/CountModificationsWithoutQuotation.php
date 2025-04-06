<?php

namespace App\Console\Commands;

use Log;
use App\Models\Users\User;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Config;
use App\Models\Modifications\Modification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ModificationsWithoutQuotationNotification;

class CountModificationsWithoutQuotation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:count-modifications-without-quotation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Storing No. of modifications without quotation per user and store the count in the notifications table';

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
        $modifications = Modification::with('quotation')->get();
        $actionOwners = $modifications->groupBy('action_owner');
        $frontendUrl = Config::get('app.frontend_url');
        foreach ($actionOwners as $key => $ownerModifications) {
            $withoutQuotations = $ownerModifications->filter(function ($item) {
                return $item['quotation'] == null;
            });

            $countOfNoQuotations = count($withoutQuotations);
            if ($countOfNoQuotations > 0) {
                $user = User::find($key);

                $operation_zones = $withoutQuotations->groupBy('oz');
                foreach ($operation_zones as $zone => $operations) {
                    $areaAwners = $this->getAreaOwners($zone);
                    $countZoneModifications = count($operations);
                    $data["message"] = "You have $countZoneModifications modification work orders without pre quotation.Attaching a soft copy of the pre-quotation to the modification's work order ensures transparency and accuracy in procurement. It helps verify that the final PO aligns with the initially agreed terms, pricing, and specifications. This practice prevents misunderstandings, reduces discrepancies, and provides a clear audit trail. By maintaining proper documentation, organizations enhance accountability, streamline approvals, and ensure compliance with procurement policies.";
                    $data["title"] = "Modifications without Quotation";
                    $data["slug"] = "You have $countZoneModifications modification work orders without pre quotation in $zone created by $user->name";
                    $data["link"] = "$frontendUrl/modifications/without/pq/$zone/$user->id";
                    Notification::send($areaAwners, new ModificationsWithoutQuotationNotification($data));
                }




                $data["message"] = "You have $countOfNoQuotations modification work orders without pre quotation.Attaching a soft copy of the pre-quotation to the modification's work order ensures transparency and accuracy in procurement. It helps verify that the final PO aligns with the initially agreed terms, pricing, and specifications. This practice prevents misunderstandings, reduces discrepancies, and provides a clear audit trail. By maintaining proper documentation, organizations enhance accountability, streamline approvals, and ensure compliance with procurement policies.";
                $data["title"] = "Modifications without Quotation";
                $data["slug"] = "You have $countOfNoQuotations modification work orders without pre quotation";
                $data["link"] = "$frontendUrl/modifications/without/pq";

                $user->notify(new ModificationsWithoutQuotationNotification($data));
            }
        }

        return 0;
    }
}
