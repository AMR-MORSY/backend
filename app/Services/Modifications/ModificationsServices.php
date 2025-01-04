<?php

namespace App\Services\Modifications;

use App\Http\Resources\ModificationResource;
use App\Models\Modifications\Modification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;



class ModificationsServices
{

    public function viewSiteModifications(object $site)
    {

        return ModificationResource::collection($site->modifications);
    }

    private function selectZone(string $zone)
    {
        if ($zone == "Cairo South") {
            return "CS";
        } elseif ($zone == "Cairo North") {
            return "CN";
        } elseif ($zone == "Cairo East") {
            return "CE";
        } elseif ($zone == "Giza") {
            return "GZ";
        }
    }

    private function createW_OCode(object $site)
    {
        $lastModification = Modification::where("oz", $site->oz)->get();
        $lastModificationId = 0;
        if (count($lastModification) > 0) {
            $lastModificationId = $lastModification->last()->id;
        }

        $zone = $this->selectZone($site->oz);
        $W_o_Code = $zone . "-00" . $lastModificationId + 1;
        return $W_o_Code;
    }
    public function createSiteModification(array $validated, object $site)
    {
        $W_o_Code = $this->createW_OCode($site);
        $validated['wo_code'] = $W_o_Code;
        $validated['oz'] = $site->oz;
        $modification = Modification::create($validated);

        return $modification;
    }

    public function reportModification(object $modification)
    {
        $modification->reported_at=Carbon::now()->format("Y-m-d");
        $modification->reported="Yes";
        $modification->save();
        return new ModificationResource($modification);
    }
    public function updateModification(object $modification, array $validated)
    {
        $modification->requester = $validated["requester"];
        $modification->subcontractor = $validated["subcontractor"];
        $modification->status = $validated["status"];
        $modification->request_date = $validated["request_date"];
        $modification->d6_date = $validated["d6_date"];
        $modification->cw_date = $validated["cw_date"];
        $modification->final_cost = $validated["final_cost"];
        $modification->est_cost = $validated["est_cost"];
        $modification->project = $validated["project"];
        $modification->actions = $validated["actions"];
        $modification->reported = $validated["reported"];
        $modification->reported_at = $validated['reported_at'];
        // $modification->wo_code = $validated["wo_code"];
        // $modification->action_owner = $validated["action_owner"];
        $modification->description = $validated["description"];
        // $modification->oz = $validated["oz"];
        $modification->save();
    }

    public function deleteModification(object $modification)
    {
        $modification->delete();
    }

    public function filterModificationsByCategory(int $action_owner = null, array $validated, string $zone=null)
    {
        if ($action_owner == null && $zone==null ) {
            $modifications = Modification::where($validated['columnName'], $validated['columnValue'])->orderBy('request_date', "desc")->get();
            return ModificationResource::collection($modifications);

        }
        elseif ($action_owner != null) {
            $modifications = Modification::where($validated['columnName'], $validated['columnValue'])->where("action_owner", $action_owner)->orderBy('request_date', "desc")->get();
            return ModificationResource::collection($modifications);
        }
        elseif($zone!=null){
            $modifications = Modification::where($validated['columnName'], $validated['columnValue'])->where('oz',$zone)->orderBy('request_date', "desc")->get();
            return ModificationResource::collection($modifications);;
        }
    }
    public function filterModificationsByDate(int $action_owner = null, array $validated, string $zone=null)
    {
        if ($action_owner == null && $zone==null && $validated['from_date']!=null && $validated['to_date']==null) {
            $modifications = Modification::where($validated['date_type'],">=" ,$validated['from_date'])->orderBy($validated['date_type'], "desc")->get();
            return ModificationResource::collection($modifications);
        }
        elseif ($action_owner == null && $zone==null && $validated['from_date']==null && $validated['to_date']!=null) {
            $modifications = Modification::where($validated['date_type'],"<=" ,$validated['to_date'])->orderBy($validated['date_type'], "desc")->get();
            return ModificationResource::collection($modifications);
        }
        if ($action_owner != null && $validated['from_date']!=null && $validated['to_date']==null) {
            $modifications = Modification::where($validated['date_type'],">=" ,$validated['from_date'])->where("action_owner", $action_owner)->orderBy($validated['date_type'], "desc")->get();
            return ModificationResource::collection($modifications);
        }
        elseif ($action_owner != null && $validated['from_date']==null && $validated['to_date']!=null) {
            $modifications = Modification::where($validated['date_type'],"<=" ,$validated['to_date'])->where("action_owner", $action_owner)->orderBy($validated['date_type'], "desc")->get();
            return ModificationResource::collection($modifications);
        }
        elseif ($action_owner != null && $validated['from_date']!=null && $validated['to_date']!=null) {
            $modifications = Modification::where($validated['date_type'],">=" ,$validated['from_date'])->where($validated['date_type'],"<=" ,$validated['to_date'])->where("action_owner", $action_owner)->orderBy($validated['date_type'], "desc")->get();
            return ModificationResource::collection($modifications);
        }
        elseif ($zone!= null && $validated['from_date']!=null && $validated['to_date']==null) {
            $modifications = Modification::where($validated['date_type'],">=" ,$validated['from_date'])->where("oz", $zone)->orderBy($validated['date_type'], "desc")->get();
            return ModificationResource::collection($modifications);
        }
        elseif ($zone!= null && $validated['from_date']==null && $validated['to_date']!=null) {
            $modifications = Modification::where($validated['date_type'],"<=" ,$validated['to_date'])->where("oz", $zone)->orderBy($validated['date_type'], "desc")->get();
            return ModificationResource::collection($modifications);
        }
        elseif ($zone!= null && $validated['from_date']!=null && $validated['to_date']!=null) {
            $modifications = Modification::where($validated['date_type'],">=" ,$validated['from_date'])->where($validated['date_type'],"<=" ,$validated['to_date'])->where("oz", $zone)->orderBy($validated['date_type'], "desc")->get();
            return ModificationResource::collection($modifications);
        }
        
    }
}
