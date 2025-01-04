<?php

namespace App\Http\Controllers\Instruments;

use Carbon\Carbon;
use App\Models\Sites\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Instruments\Instrument;
use Illuminate\Support\Facades\Validator;

class InstrumentsController extends Controller
{
    public function siteBatteriesData(Request $request)
    {
        $this->authorize("viewAny",Instrument::class);
        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:sites,site_code"]

        ]);

        if ($validator->fails()) {
            return response( $validator->getMessageBag(),422);
        } else {
            $validated = $validator->validated();
            $site = Site::where('site_code', $validated['site_code'])->first();
            $instrument = $site->instrument;
            if ($instrument) {
                return response()->json([
                    "data" => "found data",
                    "id" => $instrument->id,
                    "site_code" => $site->site_code,
                    "site_name" => $site->site_name,
                    "battery_brand" => $instrument->battery_brand,
                    "battery_volt" => $instrument->battery_volt,
                    "battery_amp_hr" => $instrument->battery_amp_hr,
                    "no_strings" => $instrument->no_strings,
                    "no_batteries" => $instrument->no_batteries,
                    "batteries_status" => $instrument->batteries_status,
                    "batt_inst_date"=>$instrument->batt_inst_date


                ], 200);
            } else {
                return response()->json([
                    "data" => "No data",


                ], 200);
            }
        }
    }

    public function updateSiteBatteriesData(Request $request)
    {
        $this->authorize("update",Instrument::class);
        $validator = Validator::make($request->all(), [
            "id" => ['required', "exists:instruments,id"],
            "battery_brand" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "battery_volt" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "battery_amp_hr" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "no_strings" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "no_batteries" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "batteries_status" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "batt_inst_date"=>["nullable","date"],


        ]);
        if ($validator->fails()) {
            return response( $validator->getMessageBag(),422);
        } else {
            $validated = $validator->validated();
            $instrument = Instrument::find($validated["id"]);
            if ($instrument) {
                $instrument->battery_brand = $validated["battery_brand"];
                $instrument->battery_volt = $validated["battery_volt"];
                $instrument->battery_amp_hr = $validated["battery_amp_hr"];
                $instrument->no_strings = $validated["no_strings"];
                $instrument->no_batteries =$validated ['no_batteries'];
                $instrument->batteries_status = $validated["batteries_status"];
                $instrument->batt_inst_date=$validated['batt_inst_date'];

                $instrument->save();

                return response()->json([
                    "message" => "updated successfully",
                    "instruments" => $instrument,
                ], 200);
            } else {
                return response()->json([
                    "message" => "site instruments not found",

                ], 204);
            }
        }
    }

    public function siteRectifierData(Request $request)
    {
        $this->authorize("viewAny",Instrument::class);
        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:sites,site_code"]

        ]);

        if ($validator->fails()) {
            return response( $validator->getMessageBag(),422);
        } else {
            $validated = $validator->validated();
            $site = Site::where('site_code', $validated['site_code'])->first();
            $instrument = $site->instrument;
            if ($instrument) {
                return response()->json([
                    "data" => "found data",
                    "id" => $instrument->id,
                    "site_code" => $site->site_code,
                    "site_name" => $site->site_name,
                    "rec_brand" => $instrument->rec_brand,
                    "module_capacity" => $instrument->module_capacity,
                    "no_module" => $instrument->no_module,
                    "pld_value" => $instrument->pld_value,
                    "net_eco" => $instrument->net_eco,
                    "net_eco_activation" => $instrument->net_eco_activation








                ], 200);
            } else {
                return response()->json([
                    "data" => "No data",


                ], 200);
            }
        }
    }

    public function updateRectifierData(Request $request)
    {
        $this->authorize("update",Instrument::class);
        $validator = Validator::make($request->all(), [
            "id" => ['required', "exists:instruments,id"],
            "rec_brand" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "module_capacity" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "no_module" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "pld_value" =>  ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "net_eco" =>  ['nullable', 'regex:/^Yes|No$/'],
            "net_eco_activation" =>  ['nullable', 'ip'],


        ]);
        if ($validator->fails()) {
            return response( $validator->getMessageBag(),422);
        } else {
            $validated = $validator->validated();
            $instrument = Instrument::find($validated["id"]);
            if ($instrument) {
                $instrument->rec_brand = $validated["rec_brand"];
                $instrument->module_capacity = $validated["module_capacity"];
                $instrument->no_module = $validated["no_module"];
                $instrument->pld_value = $validated["pld_value"];
                $instrument->net_eco = $validated["net_eco"];
                $instrument->net_eco_activation = $validated["net_eco_activation"];
                $instrument->save();
                return response()->json([
                    "message" => "updated successfully",
                    "instruments" => $instrument,
                ], 200);
            } else {
                return response()->json([
                    "message" => "site instruments not found",

                ], 204);
            }
        }
    }

    public function insertRectifierData(Request $request)
    {
        $this->authorize("store",Instrument::class);
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
            return response( $validator->getMessageBag(),422);
        } else {
            $validated = $validator->validated();
            Instrument::create($validated);
            return response()->json([
                "message"=>"inserted successfully"
            ]);

        }

    }
    public function siteDeepData(Request $request)
    {
        $this->authorize("viewAny",Instrument::class);
        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:sites,site_code"]

        ]);

        if ($validator->fails()) {
            return response( $validator->getMessageBag(),422);
        } else {
            $validated = $validator->validated();
            $site = Site::where('site_code', $validated['site_code'])->first();
            $instrument = $site->instrument;
            if ($instrument) {
                return response()->json([
                    "data" => "found data",
                    "id" => $instrument->id,
                    "site_code" => $site->site_code,
                    "site_name" => $site->site_name,
                    "on_air_date" => $instrument->on_air_date,
                    "topology" => $instrument->topology,
                    "structure"=>  $instrument->structure, 
                    "equip_room"=>$instrument->equip_room,
                    "ntra_cluster" => $instrument->ntra_cluster,
                    "care_ceo" => $instrument->care_ceo,
                    "axsees" => $instrument->axsees,
                    "serve_compound" => $instrument->serve_compound,
                    "universities"=>$instrument->universities,
                    "hot_spot"=>$instrument->hot_spot,
                    "ac1_type" => $instrument->ac1_type,
                    "ac1_hp" => $instrument->ac1_hp,
                    "ac2_type" => $instrument->ac2_type,
                    "ac2_hp" => $instrument->ac2_hp,
                    "network_type" => $instrument->network_type,
                    "last_pm_date" => $instrument->last_pm_date,
                    "need_access_permission" => $instrument->need_access_permission,
                    "permission_type" => $instrument->permission_type


                ], 200);
            } else {
                return response()->json([
                    "data" => "No data",


                ], 200);
            }
        }
    }
    public function updateSiteDeepData(Request $request)
    {
        $this->authorize("update",Instrument::class);
        $validator = Validator::make($request->all(), [
            "id" => ['required', "exists:instruments,id"],
            "on_air_date" => ['nullable', 'date'],
            "topology" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "structure"=>['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "equip_room"=>['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "ntra_cluster" =>   ['nullable', 'regex:/^Yes|No$/'],
            "care_ceo" =>  ['nullable', 'regex:/^Yes|No$/'],
            "axsees" =>  ['nullable', 'regex:/^Yes|No$/'],
            "serve_compound" =>   ['nullable', 'regex:/^Yes|No$/'],
            "universities"=>['nullable', 'regex:/^Yes|No$/'],
            "hot_spot"=>['nullable', 'regex:/^Yes|No$/'],
            "ac1_type" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "ac1_hp" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "ac2_type" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "ac2_hp" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "network_type" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "last_pm_date" => ['nullable', 'date'],
            "need_access_permission" => ['nullable', 'regex:/^Yes|No$/'],
            "permission_type" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],


        ]);
        if ($validator->fails()) {
            return response( $validator->getMessageBag(),422);
        } else {
            $validated = $validator->validated();
            $instrument = Instrument::find($validated["id"]);
            if ($instrument) {
                $instrument->on_air_date =$this->dateFormat($validated["on_air_date"]) ;
                $instrument->topology = $validated["topology"];
                $instrument->structure = $validated["structure"];
                $instrument->equip_room=$validated['equip_room'];
                $instrument->ntra_cluster = $validated["ntra_cluster"];
                $instrument->care_ceo = $validated["care_ceo"];
                $instrument->axsees = $validated["axsees"];
                $instrument->serve_compound = $validated["serve_compound"];
                $instrument->universities = $validated["universities"];
                $instrument->hot_spot = $validated["hot_spot"];
                $instrument->ac1_type = $validated["ac1_type"];
                $instrument->ac1_hp = $validated["ac1_hp"];
                $instrument->ac2_type = $validated["ac2_type"];
                $instrument->ac2_hp = $validated["ac2_hp"];
                $instrument->network_type = $validated['network_type'];
                $instrument->last_pm_date =$this->dateFormat($validated['last_pm_date']);
                $instrument->need_access_permission = $validated['need_access_permission'];
                $instrument->permission_type = $validated['permission_type'];

                $instrument->save();
                return response()->json([
                    "message" => "updated successfully",
                    "instruments" => $instrument,
                ], 200);
            } else {
                return response()->json([
                    "message" => "site instruments not found",

                ], 204);
            }
        }
    }
    public function insertSiteDeepData(Request $request)
    {
        $this->authorize("store",Instrument::class);
        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:instruments,id"],
            "on_air_date" => ['nullable', 'date'],
            "topology" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "structure"=>['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "equip_room"=>['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "ntra_cluster" =>   ['nullable', 'regex:/^Yes|No$/'],
            "care_ceo" =>  ['nullable', 'regex:/^Yes|No$/'],
            "axsees" =>  ['nullable', 'regex:/^Yes|No$/'],
            "serve_compound" =>   ['nullable', 'regex:/^Yes|No$/'],
            "universities"=>['nullable', 'regex:/^Yes|No$/'],
            "hot_spot"=>['nullable', 'regex:/^Yes|No$/'],
         
            "ac1_type" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "ac1_hp" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "ac2_type" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "ac2_hp" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "network_type" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "last_pm_date" => ['nullable', 'date'],
            "need_access_permission" => ['nullable', 'regex:/^Yes|No$/'],
            "permission_type" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],


        ]);
        if ($validator->fails()) {
            return response( $validator->getMessageBag(),422);
        } else {
            $validated = $validator->validated();
            Instrument::create($validated);
            return response()->json([
                "message"=>"inserted successfully"
            ]);

        }

    }

    public function siteMWData(Request $request)
    {
        $this->authorize("viewAny",Instrument::class);
        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:sites,site_code"]

        ]);

        if ($validator->fails()) {
            return response( $validator->getMessageBag(),422);
        } else {
            $validated = $validator->validated();
            $site = Site::where('site_code', $validated['site_code'])->first();
            $instrument = $site->instrument;
            if ($instrument) {
                return response()->json([
                    "data" => "found data",
                    "id" => $instrument->id,
                    "site_code" => $site->site_code,
                    "site_name" => $site->site_name,
                    "no_mw" => $instrument->no_mw,
                    "mw_type" => $instrument->mw_type,
                    "eband" => $instrument->eband,



                ], 200);
            } else {
                return response()->json([
                    "data" => "No data",


                ], 200);
            }
        }
    }

    public function updateMWData(Request $request)
    {
        $this->authorize("update",Instrument::class);
        $validator = Validator::make($request->all(), [
            "id" => ['required', "exists:instruments,id"],
            "no_mw" => ['required', "integer","min:0" ,"max:50"],
            "mw_type" => ['nullable', 'max:50', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "eband" => ['required', 'regex:/^Yes|No$/'],


        ]);
        if ($validator->fails()) {
            return response( $validator->getMessageBag(),422);
        } else {
            $validated = $validator->validated();
            $instrument = Instrument::find($validated["id"]);
            if ($instrument) {
                $instrument->no_mw = $validated["no_mw"];
                $instrument->mw_type = $validated["mw_type"];
                $instrument->eband = $validated["eband"];


                $instrument->save();

                return response()->json([
                    "message" => "updated successfully",
                    "instruments" => $instrument,
                ], 200);
            } else {
                return response()->json([
                    "message" => "site instruments not found",

                ], 204);
            }
        }
    }


    public function siteBTSData(Request $request)
    {
        $this->authorize("viewAny",Instrument::class);
        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:sites,site_code"]

        ]);

        if ($validator->fails()) {
            return response( $validator->getMessageBag(),422);
        } else {
            $validated = $validator->validated();
            $site = Site::where('site_code', $validated['site_code'])->first();
            $instrument = $site->instrument;
            if ($instrument) {
                return response()->json([
                    "data" => "found data",
                    "id" => $instrument->id,
                    "site_code" => $site->site_code,
                    "site_name" => $site->site_name,
                    "no_bts" => $instrument->no_bts,
                    "mrfu_2G" => $instrument->mrfu_2G,
                    "mrfu_3G" => $instrument->mrfu_3G,
                    "mrfu_4G" => $instrument->mrfu_4G,
                    "tdd" => $instrument->tdd,







                ], 200);
            } else {
                return response()->json([
                    "data" => "No data",


                ], 200);
            }
        }
    }

    public function updateSiteBTSData(Request $request)
    {
        $this->authorize("update",Instrument::class);
        $validator = Validator::make($request->all(), [
            "id" => ['required', "exists:instruments,id"],
            "no_bts" => ['required', 'integer',"min:0", 'max:50'],
            "mrfu_2G" => ['required', 'integer',"min:0", 'max:50'],
            "mrfu_3G" =>  ['required', 'integer',"min:0", 'max:50'],
            "mrfu_4G" => ['required', 'integer',"min:0", 'max:50'],
            "tdd" =>['nullable', 'regex:/^Yes|No$/'],

           

        ]);
        if ($validator->fails()) {
            return response( $validator->getMessageBag(),422);
        } else {
            $validated = $validator->validated();
            $instrument = Instrument::find($validated["id"]);
            if ($instrument) {
                $instrument->no_bts = $validated["no_bts"];
                $instrument->mrfu_2G = $validated["mrfu_2G"];
                $instrument->mrfu_3G = $validated["mrfu_3G"];
                $instrument->mrfu_4G = $validated["mrfu_4G"];
                $instrument->tdd=$validated["tdd"];
                $instrument->save();
                return response()->json([
                    "message" => "updated successfully",
                    "instruments" => $instrument,
                ], 200);
            } else {
                return response()->json([
                    "message" => "site instruments not found",

                ], 204);
            }
        }
    }

    public function sitePowerData(Request $request)
    {
        $this->authorize("viewAny",Instrument::class);
        $validator = Validator::make($request->all(), [
            "site_code" => ['required', "exists:sites,site_code"]

        ]);

        if ($validator->fails()) {
            return response( $validator->getMessageBag(),422);
        } else {
            $validated = $validator->validated();
            $site = Site::where('site_code', $validated['site_code'])->first();
            $instrument = $site->instrument;
            if ($instrument) {
                return response()->json([
                    "data" => "found data",
                    "id" => $instrument->id,
                    "site_code" => $site->site_code,
                    "site_name" => $site->site_name,
                    "power_source" => $instrument->power_source,
                    "power_meter_type" => $instrument->power_meter_type,
                    "gen_config" => $instrument->gen_config,
                    "gen_serial" => $instrument->gen_serial,
                    "gen_capacity" => $instrument->gen_capacity,
                    "overhaul_power_consumption" => $instrument->overhaul_power_consumption,


                ], 200);
            } else {
                return response()->json([
                    "data" => "No data",


                ], 200);
            }
        }
    }
    public function updateSitePowerData(Request $request)
    {
        $this->authorize("update",Instrument::class);
        $validator = Validator::make($request->all(), [
            "id" => ['required', "exists:instruments,id"],
            "power_source" =>['nullable', 'max:50', 'string'],
            "power_meter_type" => ['nullable', 'max:50', 'string'],
            "gen_config" => ['nullable', 'max:50', 'string'],
            "gen_serial" =>['nullable', 'max:50', 'string'],
            "gen_capacity" =>['nullable', 'max:50', 'string'],
            "overhaul_power_consumption" =>['nullable', 'max:100000', 'integer'],

         

           

        ]);
        if ($validator->fails()) {
            return response( $validator->getMessageBag(),422);
        } else {
            $validated = $validator->validated();
            $instrument = Instrument::find($validated["id"]);
            if ($instrument) {
                $instrument->power_source= $validated["power_source"];
                $instrument->power_meter_type = $validated["power_meter_type"];
                $instrument->gen_config = $validated["gen_config"];
                $instrument->gen_serial = $validated["gen_serial"];
                $instrument->gen_capacity=$validated["gen_capacity"];
                $instrument->overhaul_power_consumption=$validated['overhaul_power_consumption'];
                $instrument->save();
                return response()->json([
                    "message" => "updated successfully",
                    "instruments" => $instrument,
                ], 200);
            } else {
                return response()->json([
                    "message" => "site instruments not found",

                ], 204);
            }
        }

    }

    private function dateFormat($date)
    {
        if (isset($date) && !empty($date)) {
            $newDate = Carbon::parse($date);
            return $newDate = $newDate->format("Y-m-d");
        }
        else
        {
            return null;
        }
    }
}
