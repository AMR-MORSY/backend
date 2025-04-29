<?php

namespace App\Http\Controllers\Instruments;

use Carbon\Carbon;
use App\Models\Sites\Site;
use Illuminate\Http\Request;
use App\Models\Instruments\Bts;
use App\Models\Instruments\Power;
use App\Http\Controllers\Controller;
use App\Models\Instruments\DeepData;
use App\Models\Instruments\Microwave;
use App\Models\Instruments\Rectifier;
use App\Models\Instruments\Instrument;
use Illuminate\Support\Facades\Validator;

class InstrumentsController extends Controller
{
    // public function siteBatteriesData(Request $request)
    // {
    //     $this->authorize("viewAny",Instrument::class);
    //     $validator = Validator::make($request->all(), [
    //         "site_code" => ['required', "exists:sites,site_code"]

    //     ]);

    //     if ($validator->fails()) {
    //         return response( $validator->getMessageBag(),422);
    //     } else {
    //         $validated = $validator->validated();
    //         $site = Site::where('site_code', $validated['site_code'])->first();
    //         $instrument = $site->instrument;
    //         if ($instrument) {
    //             return response()->json([
    //                 "data" => "found data",
    //                 "id" => $instrument->id,
    //                 "site_code" => $site->site_code,
    //                 "site_name" => $site->site_name,
    //                 "battery_brand" => $instrument->battery_brand,
    //                 "battery_volt" => $instrument->battery_volt,
    //                 "battery_amp_hr" => $instrument->battery_amp_hr,
    //                 "no_strings" => $instrument->no_strings,
    //                 "no_batteries" => $instrument->no_batteries,
    //                 "batteries_status" => $instrument->batteries_status,
    //                 "batt_inst_date"=>$instrument->batt_inst_date


    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 "data" => "No data",


    //             ], 200);
    //         }
    //     }
    // }

    // public function updateSiteBatteriesData(Request $request)
    // {
    //     $this->authorize("update",Instrument::class);
    //     $validator = Validator::make($request->all(), [
    //         "id" => ['required', "exists:instruments,id"],
    //         "battery_brand" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
    //         "battery_volt" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
    //         "battery_amp_hr" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
    //         "no_strings" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
    //         "no_batteries" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
    //         "batteries_status" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
    //         "batt_inst_date"=>["nullable","date"],


    //     ]);
    //     if ($validator->fails()) {
    //         return response( $validator->getMessageBag(),422);
    //     } else {
    //         $validated = $validator->validated();
    //         $instrument = Instrument::find($validated["id"]);
    //         if ($instrument) {
    //             $instrument->battery_brand = $validated["battery_brand"];
    //             $instrument->battery_volt = $validated["battery_volt"];
    //             $instrument->battery_amp_hr = $validated["battery_amp_hr"];
    //             $instrument->no_strings = $validated["no_strings"];
    //             $instrument->no_batteries =$validated ['no_batteries'];
    //             $instrument->batteries_status = $validated["batteries_status"];
    //             $instrument->batt_inst_date=$validated['batt_inst_date'];

    //             $instrument->save();

    //             return response()->json([
    //                 "message" => "updated successfully",
    //                 "instruments" => $instrument,
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 "message" => "site instruments not found",

    //             ], 204);
    //         }
    //     }
    // }

    public function siteRectifierData(Request $request)
    {
        // $this->authorize("viewAny",Instrument::class);
        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:sites,site_code"]

        ]);

        if ($validator->fails()) {
            return response($validator->getMessageBag(), 422);
        } else {
            $validated = $validator->validated();
            $site = Site::where('site_code', $validated['site_code'])->first();
            $rectifier = $site->rectifier;

            return response()->json([


                "message" => 'success',
                "instrument" => $rectifier








            ], 200);
        }
    }

    public function updateRectifierData(Request $request)
    {
        // $this->authorize("update", Instrument::class);
        $validator = Validator::make($request->all(), [
            "id" => ['required', "exists:rectifier_data,id"],
            "rec_brand" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "module_capacity" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "no_module" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "pld_value" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "net_eco" =>  ['nullable', 'regex:/^Yes|No$/'],
            "net_eco_activation" =>  ['nullable', 'ip'],


        ]);
        if ($validator->fails()) {
            return response($validator->getMessageBag(), 422);
        } else {
            $validated = $validator->validated();
            $rectifier = Rectifier::find($validated["id"]);
           
                $rectifier->rec_brand = $validated["rec_brand"];
                $rectifier->module_capacity = $validated["module_capacity"];
                $rectifier->no_module = $validated["no_module"];
                $rectifier->pld_value = $validated["pld_value"];
                $rectifier->net_eco = $validated["net_eco"];
                $rectifier->net_eco_activation = $validated["net_eco_activation"];
                $rectifier->save();
                return response()->json([
                    "message" => "updated successfully",
                    "instrument" => $rectifier,
                ], 200);
        
        }
    }

    public function insertRectifierData(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:sites,site_code"],
            "rec_brand" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "module_capacity" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "no_module" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "pld_value" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "net_eco" =>  ['nullable', 'regex:/^Yes|No$/'],
            "net_eco_activation" =>  ['nullable', 'ip'],


        ]);
        if ($validator->fails()) {
            return response($validator->getMessageBag(), 422);
        } else {
            $validated = $validator->validated();
            Rectifier::create($validated);
            return response()->json([
                "message" => "inserted successfully"
            ]);
        }
    }
    public function siteDeepData(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:sites,site_code"]

        ]);

        if ($validator->fails()) {
            return response($validator->getMessageBag(), 422);
        } else {
            $validated = $validator->validated();
            $site = Site::where('site_code', $validated['site_code'])->first();
             $deepData = $site->deepData;

            return response()->json([


                "message" => 'success',
                "instrument" => $deepData








            ], 200);
        }
    }

    public function updateSiteDeepData(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            "id" => ['required', "exists:site_deep_data,id"],
            "on_air_date" => ['nullable', 'date'],
            "topology" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "structure" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "equip_room" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "ntra_cluster" =>   ['nullable', 'regex:/^Yes|No$/'],
            "care_ceo" =>  ['nullable', 'regex:/^Yes|No$/'],
            "axsees" =>  ['nullable', 'regex:/^Yes|No$/'],
            "serve_compound" =>   ['nullable', 'regex:/^Yes|No$/'],
            "universities" => ['nullable', 'regex:/^Yes|No$/'],
            "hot_spot" => ['nullable', 'regex:/^Yes|No$/'],
            "x_coordinate" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "y_coordinate" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "address" => ['nullable', 'max:1000', 'string'],
           
            "network_type" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "last_pm_date" => ['nullable', 'date'],
            "need_access_permission" => ['nullable', 'regex:/^Yes|No$/'],
            "permission_type" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],


        ]);
        if ($validator->fails()) {
            return response($validator->getMessageBag(), 422);
        } else {
            $validated = $validator->validated();
            $deepData = DeepData::find($validated["id"]);
         
               $deepData->on_air_date = $this->dateFormat($validated["on_air_date"]);
               $deepData->topology = $validated["topology"];
               $deepData->structure = $validated["structure"];
               $deepData->equip_room = $validated['equip_room'];
               $deepData->ntra_cluster = $validated["ntra_cluster"];
               $deepData->care_ceo = $validated["care_ceo"];
               $deepData->axsees = $validated["axsees"];
               $deepData->serve_compound = $validated["serve_compound"];
               $deepData->universities = $validated["universities"];
               $deepData->hot_spot = $validated["hot_spot"];
               $deepData->x_coordinate = $validated["x_coordinate"];
               $deepData->y_coordinate = $validated["y_coordinate"];
               $deepData->address = $validated["address"];
               $deepData->network_type = $validated['network_type'];
               $deepData->last_pm_date = $this->dateFormat($validated['last_pm_date']);
               $deepData->need_access_permission = $validated['need_access_permission'];
               $deepData->permission_type = $validated['permission_type'];

               $deepData->save();
                return response()->json([
                    "message" => "updated successfully",
                    "instrument" =>$deepData,
                ], 200);
           
        }
    }
    public function insertSiteDeepData(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:sites,site_code"],
            "on_air_date" => ['nullable', 'date'],
            "topology" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "structure" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "equip_room" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "ntra_cluster" =>   ['nullable', 'regex:/^Yes|No$/'],
            "care_ceo" =>  ['nullable', 'regex:/^Yes|No$/'],
            "axsees" =>  ['nullable', 'regex:/^Yes|No$/'],
            "serve_compound" =>   ['nullable', 'regex:/^Yes|No$/'],
            "universities" => ['nullable', 'regex:/^Yes|No$/'],
            "hot_spot" => ['nullable', 'regex:/^Yes|No$/'],
            "x_coordinate" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "y_coordinate" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "address" => ['nullable', 'max:1000', 'string'],
            "network_type" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "last_pm_date" => ['nullable', 'date'],
            "need_access_permission" => ['nullable', 'regex:/^Yes|No$/'],
            "permission_type" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],


        ]);
        if ($validator->fails()) {
            return response($validator->getMessageBag(), 422);
        } else {
            $validated = $validator->validated();
            $deepData=DeepData::create($validated);
            return response()->json([
                "message" => "inserted successfully",
                "instrument"=>$deepData
            ]);
        }
    }

    public function siteMWData(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:sites,site_code"]

        ]);

        if ($validator->fails()) {
            return response($validator->getMessageBag(), 422);
        } else {
            $validated = $validator->validated();
            $site = Site::where('site_code', $validated['site_code'])->first();
            $microwave = $site->microwave;

            return response()->json([


                "message" => 'success',
                "instrument" => $microwave








            ], 200);
        }
    }

    public function updateMWData(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            "id" => ['required', "exists:mw_data,id"],
            "no_mw" => ['required', "integer", "min:0", "max:50"],
            "mw_type" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "eband" => ['required', 'regex:/^Yes|No$/'],


        ]);
        if ($validator->fails()) {
            return response($validator->getMessageBag(), 422);
        } else {
            $validated = $validator->validated();
            $microwave = Microwave::find($validated["id"]);
      
                $microwave->no_mw = $validated["no_mw"];
                $microwave->mw_type = $validated["mw_type"];
                $microwave->eband = $validated["eband"];


                $microwave->save();

                return response()->json([
                    "message" => "updated successfully",
                    "instrument" => $microwave,
                ], 200);
            
        }
    }

    public function insertMWData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:sites,site_code"],
            "no_mw" => ['required', "integer", "min:0", "max:50"],
            "mw_type" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "eband" => ['required', 'regex:/^Yes|No$/'],


        ]);
        if ($validator->fails()) {
            return response($validator->getMessageBag(), 422);
        } else {
            $validated = $validator->validated();
            $microwave=Microwave::create($validated);
            return response()->json([
                "message" => "inserted successfully",
                "instrument"=> $microwave
            ]);
        }

    }


    public function siteBTSData(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:sites,site_code"]

        ]);

        if ($validator->fails()) {
            return response($validator->getMessageBag(), 422);
        } else {
            $validated = $validator->validated();
            $site = Site::where('site_code', $validated['site_code'])->first();
            $bts = $site->bts;

            return response()->json([


                "message" => 'success',
                "instrument" => $bts








            ], 200);
        }
    }

    public function updateSiteBTSData(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            "id" => ['required', "exists:bts_data,id"],
            "no_bts" => ['required', 'integer', "min:0", 'max:50'],
            "mrfu_2G" => ['required', 'integer', "min:0", 'max:50'],
            "mrfu_3G" =>  ['required', 'integer', "min:0", 'max:50'],
            "mrfu_4G" => ['required', 'integer', "min:0", 'max:50'],
            "tdd" => ['nullable', 'regex:/^Yes|No$/'],



        ]);
        if ($validator->fails()) {
            return response($validator->getMessageBag(), 422);
        } else {
            $validated = $validator->validated();
            $bts = Bts::find($validated["id"]);
            
               $bts->no_bts = $validated["no_bts"];
               $bts->mrfu_2G = $validated["mrfu_2G"];
               $bts->mrfu_3G = $validated["mrfu_3G"];
               $bts->mrfu_4G = $validated["mrfu_4G"];
               $bts->tdd = $validated["tdd"];
               $bts->save();
                return response()->json([
                    "message" => "updated successfully",
                    "instrument" => $bts,
                ], 200);
            
        }
    }
    public function insertSiteBTSData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:sites,site_code"],
            "no_bts" => ['required', 'integer', "min:0", 'max:50'],
            "mrfu_2G" => ['required', 'integer', "min:0", 'max:50'],
            "mrfu_3G" =>  ['required', 'integer', "min:0", 'max:50'],
            "mrfu_4G" => ['required', 'integer', "min:0", 'max:50'],
            "tdd" => ['nullable', 'regex:/^Yes|No$/'],



        ]);
        if ($validator->fails()) {
            return response($validator->getMessageBag(), 422);
        } else {
            $validated = $validator->validated();
            Bts::create($validated);
            return response()->json([
                "message" => "inserted successfully",
               
            ]);
        }

    }

    public function sitePowerData(Request $request)
    {
     
        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:sites,site_code"]

        ]);

        if ($validator->fails()) {
            return response($validator->getMessageBag(), 422);
        } else {
            $validated = $validator->validated();
            $site = Site::where('site_code', $validated['site_code'])->first();
            $power = $site->power;

            return response()->json([


                "message" => 'success',
                "instrument" => $power








            ], 200);
        }
    }

    public function insertSitePowerData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:sites,site_code"],
            "power_source" => ['nullable', 'max:50', 'string'],
            "power_meter_type" => ['nullable', 'max:50', 'string'],
            "gen_config" => ['nullable', 'max:50', 'string'],
            "gen_serial" => ['nullable', 'max:50', 'string'],
            "gen_capacity" => ['nullable', 'max:50', 'string'],
            "overhaul_power_consumption" => [ 'min:0', 'max:100000', 'integer'],





        ]);
        if ($validator->fails()) {
            return response($validator->getMessageBag(), 422);
        } else {
            $validated = $validator->validated();
            Power::create($validated);
            return response()->json([
                "message" => "inserted successfully",
               
            ]);

        }

    }
    public function updateSitePowerData(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
            "id" => ['required', "exists:power_data,id"],
            "power_source" => ['nullable', 'max:50', 'string'],
            "power_meter_type" => ['nullable', 'max:50', 'string'],
            "gen_config" => ['nullable', 'max:50', 'string'],
            "gen_serial" => ['nullable', 'max:50', 'string'],
            "gen_capacity" => ['nullable', 'max:50', 'string'],
            "overhaul_power_consumption" => [ 'min:0', 'max:100000', 'integer'],





        ]);
        if ($validator->fails()) {
            return response($validator->getMessageBag(), 422);
        } else {
            $validated = $validator->validated();
            $power = Power::find($validated["id"]);
         
                $power->power_source = $validated["power_source"];
                $power->power_meter_type = $validated["power_meter_type"];
                $power->gen_config = $validated["gen_config"];
                $power->gen_serial = $validated["gen_serial"];
                $power->gen_capacity = $validated["gen_capacity"];
                $power->overhaul_power_consumption = $validated['overhaul_power_consumption'];
                $power->save();
                return response()->json([
                    "message" => "updated successfully",
                    "instrument" => $power,
                ], 200);
        
        }
    }

    private function dateFormat($date)
    {
        if (isset($date) && !empty($date)) {
            $newDate = Carbon::parse($date);
            return $newDate = $newDate->format("Y-m-d");
        } else {
            return null;
        }
    }
}
