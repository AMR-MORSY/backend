<?php

namespace App\Services\Modifications;

use App\Http\Resources\ModificationResource;
use App\Models\Modifications\Action;
use App\Models\Modifications\ActionModification;
use App\Models\Modifications\Modification;
use App\Models\Modifications\ModificationReport;
use App\Models\Modifications\ModificationStatus;
use App\Models\Modifications\Project;
use App\Models\Modifications\Requester;
use App\Models\Modifications\Subcontractor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;



class ModificationsServices
{

    public function viewSiteModifications(object $site)
    {

        return ModificationResource::collection($site->modifications->load(['subcontract','actions',"proj","state","request",'report']));
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
        $lastModification = Modification::withTrashed()->where("oz", $site->oz)->get();
        $lastModificationId = 0;
        if (count($lastModification) > 0) {
            $lastModificationId = $lastModification->last()->id;
        }

        $zone = $this->selectZone($site->oz);
        $W_o_Code = $zone . "-00" . $lastModificationId + 1;
        return $W_o_Code;
    }
    public function createSiteModification(array $validated, object $site,array $actions)
    {
        $W_o_Code = $this->createW_OCode($site);
        $validated['wo_code'] = $W_o_Code;
        $validated['oz'] = $site->oz;
        $modification = Modification::create($validated);

        for($i=0; $i< count($actions); $i++)
        {
            ActionModification::create([
                "modification_id"=>$modification->id,
                "action_id"=>$actions[$i]
            ]);
        }

        return $modification;
    }

    public function reportModification(object $modification)
    {
        $modification->reported_at=Carbon::now()->format("Y-m-d");
        $modification->reported=1;
        $modification->save();
        return new ModificationResource($modification->load(['subcontract','actions',"proj","state","request",'report']));
    }
    public function updateModification(object $modification, array $validated ,array $actions)
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
        $modification->reported = $validated["reported"];
        $modification->reported_at = $validated['reported_at'];
        $modification->description = $validated["description"];
        $modification->pending=$validated['pending'];
        $modification->save();

        $actionModifications=ActionModification::where("modification_id",$modification->id)->get();
        if(count($actionModifications)>0)
        {
            foreach($actionModifications as $action)
            {
                $action->delete();
            }

            $this->createActionModification($modification,$actions);

         
        }
        else{
            $this->createActionModification($modification,$actions);

        }
    }

    private function createActionModification(object $modification, array $actions)
    {
        foreach($actions as $action)
        {
            ActionModification::create([
                "modification_id"=>$modification->id,
                "action_id"=>$action
            ]);

        }
     

    }

    public function deleteModification(object $modification)
    {
        $modification->delete();
    }


    public function filterModificationsByCategory(int $action_owner = null, array $validated, string $zone=null)
    {
        if($validated['columnName']=='actions')
        {
            if ($action_owner == null && $zone==null ) {
                $action = Action::find($validated['columnValue']);
                $modifications=$action->modifications->load(['subcontract','actions',"proj","state","request",'report']);
                return ModificationResource::collection($modifications);
    
            }
            elseif ($action_owner != null) {
                $action = Action::find($validated['columnValue']);
                $modifications=$action->modifications->load(['subcontract','actions',"proj","state","request",'report']);
                return ModificationResource::collection($modifications);
            }
            elseif($zone!=null){
                $action = Action::find($validated['columnValue']);
                $modifications=$action->modifications->load(['subcontract','actions',"proj","state","request",'report']);
                return ModificationResource::collection($modifications);;
            }
         

        }
        else{
            if ($action_owner == null && $zone==null ) {
                $modifications = Modification :: with(['subcontract',"request",'proj','state','report','actions'])->where($validated['columnName'], $validated['columnValue'])->orderBy('request_date', "desc")->get();
                return ModificationResource::collection($modifications);
    
            }
            elseif ($action_owner != null) {
                $modifications = Modification::with(['subcontract',"request",'proj','state','report','actions'])->where($validated['columnName'], $validated['columnValue'])->where("action_owner", $action_owner)->orderBy('request_date', "desc")->get();
                return ModificationResource::collection($modifications);
            }
            elseif($zone!=null){
                $modifications = Modification::with(['subcontract',"request",'proj','state','report','actions'])->where($validated['columnName'], $validated['columnValue'])->where('oz',$zone)->orderBy('request_date', "desc")->get();
                return ModificationResource::collection($modifications);;
            }

        }
       
    }
    public function filterModificationsByDate(int $action_owner = null, array $validated, string $zone=null)
    {
        if ($action_owner == null && $zone==null && $validated['from_date']!=null && $validated['to_date']==null) {
            $modifications = Modification::where($validated['date_type'],">=" ,$validated['from_date'])->orderBy($validated['date_type'], "desc")->get();
            return ModificationResource::collection($modifications->load(['subcontract','actions',"proj","state","request",'report']));
        }
        elseif ($action_owner == null && $zone==null && $validated['from_date']==null && $validated['to_date']!=null) {
            $modifications = Modification::where($validated['date_type'],"<=" ,$validated['to_date'])->orderBy($validated['date_type'], "desc")->get();
            return ModificationResource::collection($modifications->load(['subcontract','actions',"proj","state","request",'report']));
        }
        if ($action_owner != null && $validated['from_date']!=null && $validated['to_date']==null) {
            $modifications = Modification::where($validated['date_type'],">=" ,$validated['from_date'])->where("action_owner", $action_owner)->orderBy($validated['date_type'], "desc")->get();
            return ModificationResource::collection($modifications->load(['subcontract','actions',"proj","state","request",'report']));
        }
        elseif ($action_owner != null && $validated['from_date']==null && $validated['to_date']!=null) {
            $modifications = Modification::where($validated['date_type'],"<=" ,$validated['to_date'])->where("action_owner", $action_owner)->orderBy($validated['date_type'], "desc")->get();
            return ModificationResource::collection($modifications->load(['subcontract','actions',"proj","state","request",'report']));
        }
        elseif ($action_owner != null && $validated['from_date']!=null && $validated['to_date']!=null) {
            $modifications = Modification::where($validated['date_type'],">=" ,$validated['from_date'])->where($validated['date_type'],"<=" ,$validated['to_date'])->where("action_owner", $action_owner)->orderBy($validated['date_type'], "desc")->get();
            return ModificationResource::collection($modifications->load(['subcontract','actions',"proj","state","request",'report']));
        }
        elseif ($zone!= null && $validated['from_date']!=null && $validated['to_date']==null) {
            $modifications = Modification::where($validated['date_type'],">=" ,$validated['from_date'])->where("oz", $zone)->orderBy($validated['date_type'], "desc")->get();
            return ModificationResource::collection($modifications->load(['subcontract','actions',"proj","state","request",'report']));
        }
        elseif ($zone!= null && $validated['from_date']==null && $validated['to_date']!=null) {
            $modifications = Modification::where($validated['date_type'],"<=" ,$validated['to_date'])->where("oz", $zone)->orderBy($validated['date_type'], "desc")->get();
            return ModificationResource::collection($modifications->load(['subcontract','actions',"proj","state","request",'report']));
        }
        elseif ($zone!= null && $validated['from_date']!=null && $validated['to_date']!=null) {
            $modifications = Modification::where($validated['date_type'],">=" ,$validated['from_date'])->where($validated['date_type'],"<=" ,$validated['to_date'])->where("oz", $zone)->orderBy($validated['date_type'], "desc")->get();
            return ModificationResource::collection($modifications->load(['subcontract','actions',"proj","state","request",'report']));
        }
        
    }


    public function getSubcontractors()
    {
        $subcontractors=Subcontractor::all();
        return $subcontractors;

    }
    
    public function getRequesters()
    {
        $requesters=Requester::all();
        return $requesters;

    }
    
    public function getActions()
    {
        $actions=Action::all();
        return $actions;

    }
    
    public function getProjects()
    {
        $projects=Project::all();
        return $projects;

    }

    public function getModificationStatus()
    {
        $status=ModificationStatus::all();
        return $status;
    }
    public function getReportedModification()
    {
        $reported=ModificationReport::all();
        return $reported;
    }
}
