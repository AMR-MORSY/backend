<?php

namespace App\Http\Controllers\Sites;

use App\Models\Sites\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cascade;
use App\Models\Nodal;
use ArrayIterator;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToArray;

class NormalUsersSitesController extends Controller
{
    public function search($search)
    {
        if ($search == "null") {
            $search = null;
        }
        $data = [
            "search" => $search
        ];


        $validator = Validator::make($data, ["search" => ['required', 'regex:/^[\w\d\s-]+$/', 'max:30']]);
        if ($validator->fails()) {
            return response()->json(
                [
                    "errors" => $validator->getMessageBag()->toArray(),

                ],
                422
            );
            $this->throwValidationException(


                $validator

            );
        } else {
            $validated = $validator->validated();
            $search = $validated['search'];
            $sites = Site::where('site_code', 'like', "%$search%")->orWhere('site_name', 'like', "%$search%")->get();
            if (count($sites) != 0) {


                return response()->json([
                    "sites" => $sites,
                    "message" => "success",
                ], 200);
            } else {
                return response()->json(
                    [
                        "data" => $data,

                        "message" => 'No data Found',

                    ],
                    200
                );
            }
        }
    }

    public function siteDetails($siteCode)
    {
        if ($siteCode == "null") {
            $siteCode = null;
        }
        $data = [
            "site_code" => $siteCode
        ];
        $validator = Validator::make($data, ["site_code" => ['required', "exists:sites,site_code"]]);
        if ($validator->fails()) {
            return response()->json(
                [
                    "errors" => $validator->getMessageBag()->toArray(),

                ],
                422
            );
            $this->throwValidationException(


                $validator

            );
        } else {
            $validated = $validator->validated();
            $siteCode = $validated['site_code'];

            $site = Site::where("site_code", $siteCode)->first();
          
            if ($site) {
                /////////////////////this part of code to git the Nodal that the site is cascaded on////
                $originalCascade=Cascade::where("cascade_code",$siteCode)->first();
                if($originalCascade)
                {
                    $nodalCode=$originalCascade->nodal_code;
                    $originalNodal=Site::where("site_code",$nodalCode)->first();
                    $nodalName=$originalNodal->site_name;
                    $site['nodal_code']=$nodalCode;
                    $site['nodal_name']=$nodalName;
    
                }
                else{
                    $site['nodal_code']=null;
                    $site['nodal_name']=null;
    
                }
                //////////////////////////////////////////////////////////////////////////////
                if (isset($site->nodal->cascades)) {
                    $directCascades = $site->nodal->cascades;
                    $indirectCascades = [];


                    $directCascadesArray = $directCascades->toArray();

                    $indirectCascadesContainer = new ArrayIterator($directCascadesArray);
                    $i = 0;
                    while (count($indirectCascadesContainer) > 0) {

                        $nodal = Nodal::where("nodal_code", $indirectCascadesContainer[$i]["cascade_code"])->first();
                        if (isset($nodal)) {

                            foreach ($nodal->cascades as $cascade) {
                                $cascade_info=Site::where("site_code", $cascade["cascade_code"])->first();
                                if(isset($cascade_info))/////if the cascade code does not exist in sites table
                                {
                                    $cascade_category=$cascade_info->category;
                                    $cascade["category"]=$cascade_category;
                                    array_push($indirectCascades, $cascade);
                                    $indirectCascadesContainer->append($cascade);

                                }
                               
                            }
                        }




                        $indirectCascadesContainer->offsetUnset($i);
                        $i++;
                    }
                    $newDirectCascades = [];
                    foreach ($directCascades as $cascade) {
                        $cascade_info=Site::where("site_code", $cascade["cascade_code"])->first();
                        if(isset($cascade_info))/////if the cascade code does not exist in sites table
                        {
                            $cascade_category=$cascade_info->category;
                            $cascade["category"]=$cascade_category;
                            $nodal = Nodal::where("nodal_code", $cascade["cascade_code"])->first();
                            if (isset($nodal)) {
                                $CountCascades = $nodal->cascades->count();
                                $cascade['countCascades'] = $CountCascades;
                                array_push($newDirectCascades, $cascade);
                            } else {
                                array_push($newDirectCascades, $cascade);
                            }
                        }
                       
                    }



                    return response()->json([
                        "message" => "success",
                        "site" => $site,
                        "cascades" => $newDirectCascades,
                        "indirectCascades" => $indirectCascades

                    ], 200);
                } else {
                    return response()->json([
                        "message" => "success",
                        "site" => $site,
                        "cascades" => [],
                        "indirectCascades" => [],


                    ], 200);
                }
            } else {
                return response()->json([
                    "message" => "Site Not Found",

                ], 404);
            }
        }
    }

    public function index()
    {
        $sites=Site::all();
        $total_sites_count=$sites->count();
        $categories=$sites->groupBy("category");
        $statestics=[];
        

        foreach($categories as $category=>$sites)
        {
           
            if($category=="NDL")
            {
               $statestics["NDL"]=$sites->count();
              
            }
            if($category=="VIP")
            {
               $statestics["VIP"]=$sites->count();
                
            }
            if($category=="VIP + NDL")
            {
               $statestics["VIP_NDL"]=$sites->count();
               
            }
            if($category=="LDN")
            {
               $statestics["LDN"]=$sites->count();
            }
               
           
        }
        

        return response()->json([
            "total_sites"=>$total_sites_count,
            "statestics"=>$statestics
        ],200);

    }
}
