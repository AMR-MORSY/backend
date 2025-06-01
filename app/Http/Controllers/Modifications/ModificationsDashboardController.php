<?php

namespace App\Http\Controllers\Modifications;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Modifications\Modification;
use App\Services\Modifications\ModificationsDashboardServices;

class ModificationsDashboardController extends Controller
{
    protected $modificationDashboardServices;
    public function __construct(ModificationsDashboardServices $modificationDashboardServices)
    {
        $this->modificationDashboardServices = $modificationDashboardServices;
    }

    private function getYears()
    {
        $years = Modification::all()->groupBy('year')->keys();
        $years = $years->sortDesc();
        $years =  $years->values()->all();
        return $years;
    }

    public function years(Request $request)
    {


        if ($request->user()->can('viewAllModifications', Modification::class)) {
            $years = $this->getYears();
            return response()->json([
                "message" => "success",
                "years" => $years
            ], 200);
        } else {
            abort(403);
        }
    }

    public function dashboard(Request $request,$year)
    {
        if ($request->user()->can('viewAllModifications', Modification::class)) {

            $dashboard=[];

            $years=$this->getYears();

            $dashboard['years']=$years;

            $modifications=Modification::where('year',$year)->get();
            $modificationsWithItems=Modification::with('quotation.prices')->where('status',1)->where('year',$year)->get();

            $dashboard['status']=$this->modificationDashboardServices->modificationStatus($modifications);
            $dashboard['subcontractor']=$this->modificationDashboardServices->subcontractors($modifications);
            $dashboard['owners']=$this->modificationDashboardServices->actionOwners($modifications);
            $dashboard['projects']=$this->modificationDashboardServices->projects($modifications);
            $dashboard['items']=$this->modificationDashboardServices->usedItems($modificationsWithItems);

            return response()->json([

                "message"=>"success",
                "dashboard"=>$dashboard


            ]);
        }
        else{
            abort(403);
        }

    }
}
