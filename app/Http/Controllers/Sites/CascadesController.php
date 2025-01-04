<?php

namespace App\Http\Controllers\Sites;

use App\Models\Nodal;
use App\Models\Cascade;
use App\Models\Sites\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Sites\CascadesImport;
use App\Exports\sites\AllCascadesExport;
use Illuminate\Support\Facades\Validator;

class CascadesController extends Controller
{
    public function index(Request $request)
    {
        return  Cascade::all();
    }

    public function __construct()
    {
        $this->middleware("role:super-admin|admin");
    }
    public function exportAllCascades()
    {

        return new AllCascadesExport();
    }

    private function checkSiteCascades($siteCode, $cascades)
    {
        $siteCascades = [];
        $newCascades = [];
        foreach ($cascades as $cascade) {
            $site = Cascade::where("nodal_code", $siteCode)->where("cascade_code", $cascade["cascade_code"])->first();
            if ($site) {
                array_push($siteCascades, $cascade);
            } else {
                array_push($newCascades, $cascade);
            }
        }

        $data["siteCascades"] = $siteCascades;
        $data["newCascades"] = $newCascades;

        return $data;
    }

    private function checkNewCascades($cascades)
    {
        $errors = [];
        $newCascades=[];
        foreach ($cascades as $cascade) {
            $site = Cascade::where("cascade_code", $cascade['cascade_code'])->first();
            if($site)
            {
                $cascadeCode=$cascade["cascade_code"];
                $cascadeName=$cascade['cascade_name'];
                array_push($errors,"site $cascadeCode $cascadeName already cascaded");
            }
            else
            {
                array_push($newCascades,$cascade);
            }
        }
       $data["errors"]=$errors;
       $data['newCascades']=$newCascades;

       return $data;

    }

    public function updateCascades(Request $request)
    {
        if(count ($request->input('cascades'))==0)
        {
            $validator = Validator::make($request->all(),[ "siteCode" => ["required", "exists:sites,site_code"]]);
            if ($validator->fails()) {
                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorect values in the form!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 422);
            } else {
                $validated = $validator->validated();
                $nodalCascades=Cascade::where("nodal_code",$validated["siteCode"])->get();
                if(count($nodalCascades)>0) 
                {
                    foreach($nodalCascades as $cascade) 
                    {
                        $cascade->delete();
                    }
                }
                $nodal=Nodal::where("nodal_code",$validated["siteCode"])->first();
                $nodal->delete();
                return response()->json([
                    "message"=>"Nodal deleted successfully",
                ],200);
            }
           

        }
        else{
           
            $rules = [

                // "cascades.*.cascade_code" => ['required', "exists:sites,site_code"],
                // "cascades.*.cascade_name" => ['required', "exists:sites,site_name"],
                // "siteCode" => ["required", "exists:sites,site_code"],

                "cascades.*.cascade_code" => ['required'],
                "cascades.*.cascade_name" => ['required'],
                "siteCode" => ["required"],
    
            ];
    
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorect values in the form!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 422);
            } else {
                $validated = $validator->validated();


                $data = $this->checkSiteCascades($validated["siteCode"], $validated['cascades']);
                $finalCascades=[];
                if (count($data["newCascades"]) > 0) {
                    $newCascades=$this->checkNewCascades($data["newCascades"]);
    
                    if(count($newCascades['errors'])>0)
                    {
                        return response()->json([
                            "errors" => $newCascades['errors'],
                        ],406);
    
                    }
                    if(count($newCascades['newCascades'])>0)
                    {
                        foreach($newCascades['newCascades'] as $cascade)
                        {
                            array_push($finalCascades,$cascade);
        
        
                        }
                    }
                  
                }
             
                if(count($data["siteCascades"])>0) 
                {
                    foreach($data["siteCascades"] as $cascade)
                    {
                        array_push($finalCascades,$cascade);
    
                    }
                    
                }
               
                $nodalCascades=Cascade::where("nodal_code",$validated["siteCode"])->get();
                if(count($nodalCascades)>0) 
                {
                    foreach($nodalCascades as $cascade) 
                    {
                        $cascade->delete();
                    }
                    foreach($finalCascades as $cascade)
                    {
                        Cascade::create([
                            "nodal_code"=>$validated["siteCode"],
                            "cascade_code"=>$cascade["cascade_code"],
                            "cascade_name"=>$cascade["cascade_name"],
                        
                        ]);
                    }
                    return response()->json([
                        "message" => "UpdatedSuccessfully",
                    ],200);
                }
                else{
                    $nodal=Nodal::where("nodal_code",$validated["siteCode"])->first();
                    $site_name=Site::where("site_code",$validated["siteCode"])->first()->site_name;
                    if(!isset($nodal))
                    {
                        Nodal::create([
                            "site_code"=>$validated["siteCode"],
                            "nodal_code"=>$validated["siteCode"],
                            "nodal_name"=>$site_name,
    
                        ]);
                    }
                    foreach($finalCascades as $cascade)
                    {
                        Cascade::create([
                            "nodal_code"=>$validated["siteCode"],
                            "cascade_code"=>$cascade["cascade_code"],
                            "cascade_name"=>$cascade["cascade_name"],
                        
                        ]);
                    }
                    return response()->json([
                        "message" => "Updated Successfully",
                    ],200);
    
                }
    
               
             }

        }
       
     
    }

    public function importCascades(Request $request)
    {
        $import = new CascadesImport();

        $validator = Validator::make($request->all(), ["cascades" => 'required|mimes:csv,xlsx']);
        $validated = $validator->validated();



        if ($validated) {

            try {
                Excel::import($import,  $validated["cascades"]);


                return response()->json([
                    "message" => "inserted Succesfully",
                ]);
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $failures = $e->failures();
                $errors = [];
                $error = [];

                foreach ($failures as $failure) {
                    $error['row'] = $failure->row(); // row that went wrong
                    $error['attribute'] = $failure->attribute(); // either heading key (if using heading row concern) or column index
                    $error['errors'] = $failure->errors(); // Actual error messages from Laravel validator
                    $error['values'] = $failure->values(); // The values of the row that has failed.
                    array_push($errors, $error);
                }
                return response()->json([
                    "sheet_errors" => $errors,
                ], 422);
            }
        } else {
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);


            $this->throwValidationException(

                $request,
                $validator

            );
        }
    }
}
