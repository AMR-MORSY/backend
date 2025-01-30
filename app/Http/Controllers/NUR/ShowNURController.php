<?php

namespace App\Http\Controllers\NUR;

use stdClass;
use App\Models\NUR\FM2G;
use App\Models\NUR\FM3G;
use App\Models\NUR\FM4G;
use App\Models\NUR\NUR2G;
use App\Models\NUR\NUR3G;
use App\Models\NUR\NUR4G;
use App\Models\Sites\Site;
use Illuminate\Http\Request;
use App\Services\NUR\WeeklyNUR;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;
use App\Services\NUR\NURStatestics\ZoneWeeklyStatestics;
use App\Services\NUR\NURStatestics\CairoWeeklyStatestics;
use App\Services\NUR\NURStatestics\CairoYearlyStatestics;
use Maatwebsite\Excel\Concerns\ToArray;

class ShowNURController extends Controller
{



    public function show_nur($week, $year, $NUR_Type)
    {
        $this->authorize("viewAny", NUR2G::class);
        $data = [

            "week" => $week,

            "year" => $year,

            "NUR_Type"=>$NUR_Type
        ];


        $validator = Validator::make($data, ["week" => ["required", 'integer', "between:1,52"], "year" => ['required', 'regex:/^2[0-9]{3}$/'], "NUR_Type" => ["required", "in:NUR,FM"]]);




        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        } else {
            $validated = $validator->validated();

            $weeklyNUR = $this->getWeeklyNUR($validated['week'], $validated['year'], $validated['NUR_Type']);

            if (isset($weeklyNUR['error'])) {
                return response()->json([
                    "errors" => $weeklyNUR['errors'],

                ], 200);
            } else {
                return response()->json([

                    "NUR" => $weeklyNUR,


                ], 200);
            }
        }
    }

    
    private function collectAllWeekTickets($total_week_tickets_2G, $total_week_tickets_3G, $total_week_tickets_4G) ///returns a collection of all technology tickets
    {


        $allTickets = [];

        foreach ($total_week_tickets_2G as $ticket) {
            array_push($allTickets, $ticket);
        }
        foreach ($total_week_tickets_3G as $ticket) {
            array_push($allTickets, $ticket);
        }

        foreach ($total_week_tickets_4G as $ticket) {
            array_push($allTickets, $ticket);
        }

        $allTickets = collect($allTickets);
        return  $allTickets;
    }

    private function getCairoWeekTechTickets($week, $year, $NUR_Type): array /////this function returns total week tickets for each technology
    {
        if($NUR_Type=="NUR")////// the required ticket either for FM or network NUR 
        {
            $total_week_tickets_2G = NUR2G::where('year', $year)->where('week', $week)->where("oz", "CAIRO SOUTH")->orWhere('year', $year)->where('week', $week)->where("oz", "CAIRO NORTH")->orWhere('year', $year)->where('week', $week)->where("oz", "CAIRO EAST")->orWhere('year', $year)->where('week', $week)->where("oz", "GIZA")->get(); /////collection

            $total_week_tickets_3G = NUR3G::where('year', $year)->where('week', $week)->where("oz", "CAIRO SOUTH")->orWhere('year', $year)->where('week', $week)->where("oz", "CAIRO NORTH")->orWhere('year', $year)->where('week', $week)->where("oz", "CAIRO EAST")->orWhere('year', $year)->where('week', $week)->where("oz", "GIZA")->get();
    
            $total_week_tickets_4G = NUR4G::where('year', $year)->where('week', $week)->where("oz", "CAIRO SOUTH")->orWhere('year', $year)->where('week', $week)->where("oz", "CAIRO NORTH")->orWhere('year', $year)->where('week', $week)->where("oz", "CAIRO EAST")->orWhere('year', $year)->where('week', $week)->where("oz", "GIZA")->get();

        }
        else{
            $total_week_tickets_2G = FM2G::where('year', $year)->where('week', $week)->where("oz", "CAIRO SOUTH")->orWhere('year', $year)->where('week', $week)->where("oz", "CAIRO NORTH")->orWhere('year', $year)->where('week', $week)->where("oz", "CAIRO EAST")->orWhere('year', $year)->where('week', $week)->where("oz", "GIZA")->get(); /////collection

            $total_week_tickets_3G = FM3G::where('year', $year)->where('week', $week)->where("oz", "CAIRO SOUTH")->orWhere('year', $year)->where('week', $week)->where("oz", "CAIRO NORTH")->orWhere('year', $year)->where('week', $week)->where("oz", "CAIRO EAST")->orWhere('year', $year)->where('week', $week)->where("oz", "GIZA")->get();
    
            $total_week_tickets_4G = FM4G::where('year', $year)->where('week', $week)->where("oz", "CAIRO SOUTH")->orWhere('year', $year)->where('week', $week)->where("oz", "CAIRO NORTH")->orWhere('year', $year)->where('week', $week)->where("oz", "CAIRO EAST")->orWhere('year', $year)->where('week', $week)->where("oz", "GIZA")->get();

            
        }
     

        $total_week['tickets_2G'] = $total_week_tickets_2G;
        $total_week['tickets_3G'] = $total_week_tickets_3G;
        $total_week['tickets_4G'] = $total_week_tickets_4G;

        return $total_week;
    }

    private function getAllWeekFMTickets($week, $year)
    {
        $total_week_tickets_2G = FM2G::where('year', $year)->where('week', $week)->get(); /////collection

        $total_week_tickets_3G = FM3G::where('year', $year)->where('week', $week)->get();

        $total_week_tickets_4G = FM4G::where('year', $year)->where('week', $week)->get();
        $total_week['tickets_2G'] = $total_week_tickets_2G;
        $total_week['tickets_3G'] = $total_week_tickets_3G;
        $total_week['tickets_4G'] = $total_week_tickets_4G;

        return $total_week;


    }
    private function getAllWeekNURTickets($week,$year)
    {
        $total_week_tickets_2G = NUR2G::where('year', $year)->where('week', $week)->get(); /////collection

        $total_week_tickets_3G = NUR3G::where('year', $year)->where('week', $week)->get();

        $total_week_tickets_4G = NUR4G::where('year', $year)->where('week', $week)->get();
        $total_week['tickets_2G'] = $total_week_tickets_2G;
        $total_week['tickets_3G'] = $total_week_tickets_3G;
        $total_week['tickets_4G'] = $total_week_tickets_4G;

        return $total_week;

    }
    private function getWeeklyNUR($week, $year, $NUR_Type)
    {

        $total_week = $this->getCairoWeekTechTickets($week, $year, $NUR_Type);

        $errors = [];
        if (count($total_week['tickets_2G']) <= 0) {
            array_push($errors, "2G NUR does not exist");
        }
        if (count($total_week['tickets_3G']) <= 0) {
            array_push($errors, "3G NUR does not exist");
        }
        if (count($total_week['tickets_4G']) <= 0) {
            array_push($errors, "4G NUR does not exist");
        }
        if (count($errors) > 0) {
            $notFound["error"] = true;
            $notFound["errors"] = $errors;
            return $notFound;
        } else {

            $allTickets = $this->collectAllWeekTickets($total_week['tickets_2G'], $total_week['tickets_3G'], $total_week['tickets_4G']);
            $NUR['NUR2G'] = ZoneWeeklyStatestics::NUR2GStatestics($total_week['tickets_2G']);
            $NUR['NUR3G'] = ZoneWeeklyStatestics::NUR3GStatestics($total_week['tickets_3G']);
            $NUR['NUR4G'] = ZoneWeeklyStatestics::NUR4GStatestics($total_week['tickets_4G']);
            $NUR['combined'] = ZoneWeeklyStatestics::combinedNUR($allTickets);
            if($NUR_Type =="NUR")
            {
                $NUR["zonesSubsystem"] = ZoneWeeklyStatestics::zonesSubsystemNUR($allTickets);
                $NUR["cairoSubsystem"]=CairoWeeklyStatestics::cairoSubsystem($allTickets);
                $NUR["zonesSubsystemCountTickts"] = ZoneWeeklyStatestics::zonesSubsystemCountTickts($allTickets);
                $NUR['officesNUR']=ZoneWeeklyStatestics::officesNUR($allTickets);
                // $NUR["zonesResponseWithAccess"] = ZoneWeeklyStatestics::zonesResponseWithAccess($allTickets);
                // $NUR["zonesResponseWithoutAccess"] = ZoneWeeklyStatestics::zonesResponseWithoutAccess($allTickets);
                $NUR["zonesGeneratorStatestics"] = ZoneWeeklyStatestics::zonesGeneratorStatestics($allTickets);
                $NUR["topRepeated"] = ZoneWeeklyStatestics::zonesTopRepeated($allTickets);
                $NUR["cairoTopRepeated"]=CairoWeeklyStatestics::cairoTopRepeated($allTickets);
                $NUR["topNUR"] = ZoneWeeklyStatestics::zonesTopNUR($allTickets);
               

            }
            else{
                $total_week_NUR=$this->getAllWeekNURTickets($week,$year);
                $total_week_FM=$this->getAllWeekFMTickets($week,$year);
                $allWeekNURTickets = $this->collectAllWeekTickets($total_week_NUR['tickets_2G'], $total_week_NUR['tickets_3G'], $total_week_NUR['tickets_4G']);
               
                $allWeekFMTickets=$this->collectAllWeekTickets($total_week_FM['tickets_2G'], $total_week_FM['tickets_3G'], $total_week_FM['tickets_4G']);
                $NUR['workGroupCompar']=CairoWeeklyStatestics::workGroupFMComparison($allWeekFMTickets);
            }

         
            return $NUR;
        }
    }



    public function siteNUR(Request $request)
    {
        $this->authorize("viewAny", NUR2G::class);
        $validator = validator::make($request->all(), ["site_code" => ["required", "regex:/^([0-9a-zA-Z]{4,6}(up|UP))|([0-9a-zA-Z]{4,6}(ca|CA))|([0-9a-zA-Z]{4,6}(de|DE))$/"]]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        } else {
            $validated = $validator->validated();
            $site_2GNUR = NUR2G::where("problem_site_code", $validated["site_code"])->get();
            $site_3GNUR = NUR3G::where("problem_site_code", $validated["site_code"])->get();
            $site_4GNUR = NUR4G::where("problem_site_code", $validated["site_code"])->get();
            $site_2GFM=FM2G::where("problem_site_code", $validated["site_code"])->get();
            $site_3GFM=FM3G::where("problem_site_code", $validated["site_code"])->get();
            $site_4GFM=FM4G::where("problem_site_code", $validated["site_code"])->get();


            return response()->json([
                "NUR2G" => $site_2GNUR,
                "NUR3G" => $site_3GNUR,
                "NUR4G" => $site_4GNUR,
                "FM2G"=>$site_2GFM,
                "FM3G"=>$site_3GFM,
                "FM4G"=>$site_4GFM
            ], 200);
        }
    }
    public function vipSitesWeeklyNUR($zone, $week, $year)
    {
        $this->authorize("viewAny", NUR2G::class);
        $data = [
            "zone" => $zone,
            "week" => $week,
            "year" => $year
        ];
        $validator = Validator::make($data, ["week" => ["required", 'integer', "between:1,52"], "zone" => ['required', 'regex:/^Cairo East|Cairo South|Cairo North|Giza$/'], "year" => ['required', 'regex:/^2[0-9]{3}$/']]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        } else {
            $validated = $validator->validated();
            $vip_sites = Site::where("oz", $validated["zone"])->where("category", "VIP")->orWhere("oz", $validated["zone"])->where("category", "VIP + NDL")->get();
            $vip_sites_codes = $vip_sites->groupBy("site_code")->keys();
            $vip_sites_names = $vip_sites->groupBy("site_name")->keys();

            $sites = $this->getVipORNodalNUR($vip_sites_codes, $vip_sites_names, $week, $validated['year']);




            return response()->json([
                "sites" => $sites
            ], 200);
        }
    }
    public function nodalSitesWeeklyNUR($zone, $week, $year)
    {
        $this->authorize("viewAny", NUR2G::class);
        $data = [
            "zone" => $zone,
            "week" => $week,
            "year" => $year
        ];
        $validator = Validator::make($data, ["week" => ["required", 'integer', "between:1,52"], "zone" => ['required', 'regex:/^Cairo East|Cairo South|Cairo North|Giza$/'], "year" => ['required', 'regex:/^2[0-9]{3}$/']]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        } else {
            $validated = $validator->validated();
            $vip_sites = Site::where("oz", $validated["zone"])->where("category", "NDL")->orWhere("oz", $validated["zone"])->where("category", "VIP + NDL")->get();
            $nodal_sites_codes = $vip_sites->groupBy("site_code")->keys();
            $nodal_sites_names = $vip_sites->groupBy("site_name")->keys();
            $sites = $this->getVipORNodalNUR($nodal_sites_codes, $nodal_sites_names, $week, $validated['year']);
            return response()->json([
                "sites" => $sites
            ], 200);
        }
    }

    private function getVipORNodalNUR($vip_nodal_codes, $vip_nodal_names, $week, $year)
    {
        $count_vip_codes = count($vip_nodal_codes);
        $sites = [];


        for ($i = 0; $i < $count_vip_codes; $i++) {
            $vip = [];
            $allTickets = [];
            $NUR3G = NUR3G::where("problem_site_code", $vip_nodal_codes[$i])->where("week", $week)->where("year", $year)->get();
            $NUR2G = NUR2G::where("problem_site_code", $vip_nodal_codes[$i])->where("week", $week)->where("year", $year)->get();

            $NUR4G = NUR4G::where("problem_site_code", $vip_nodal_codes[$i])->where("week", $week)->where("year", $year)->get();

            if (count($NUR3G) > 0) {
                $vip["site_name"] = $vip_nodal_names[$i];
                $vip["site_code"] = $vip_nodal_codes[$i];
               
                $vip["NUR_3G_tickets"] = $NUR3G;
            }

            if (count($NUR2G) > 0) {
                $vip["site_name"] = $vip_nodal_names[$i];
                $vip["site_code"] = $vip_nodal_codes[$i];
              
                $vip["NUR_2G_tickets"] = $NUR2G;
            }

            if (count($NUR4G) > 0) {
                $vip["site_name"] = $vip_nodal_names[$i];
                $vip["site_code"] = $vip_nodal_codes[$i];
       
                $vip["NUR_4G_tickets"] = $NUR4G;
            }
            $allTickets = $this->collectAllWeekTickets($NUR2G, $NUR3G, $NUR4G);
            if (count($allTickets) > 0) {
                $NUR_c = number_format($allTickets->sum("nur_c"), 2, '.', ',');
                $vip['NUR_c'] = $NUR_c;
            }

            if (count($vip) > 0) {
                array_push($sites, $vip);
            }
        }
        return $sites;
    }







    //////////////////////////////////////////////Yearly Network NUR//////////////////////////////
    public function cairoYearlyNUR_C($year)
    {
        $this->authorize("viewAny", NUR2G::class);

        $allTickets = CairoYearlyStatestics::allYearTickets($year);
     
         $cairoAllTickets=$allTickets->where("work_group","HLGAM");
      
        
         $NUR_C_yearly = CairoYearlyStatestics::cairoNUR_C(  $cairoAllTickets, $year);
        return response()->json([
            "NUR_C_yearly" => $NUR_C_yearly
        ], 200);
    }
    public function cairoModificationYearlyNUR($year)
    {
        $this->authorize("viewAny", NUR2G::class);

        $allTickets = CairoYearlyStatestics::allYearTickets($year);
        $cairoAllTickets=$allTickets->where("work_group","HLGAM");
        $NUR_Gen_yearly = CairoYearlyStatestics::cairoModificationNUR($cairoAllTickets, $year);
        return response()->json([
            "NUR_C_yearly" => $NUR_Gen_yearly
        ], 200);
    }
    public function cairoPowerYearlyNUR($year)
    {
        $this->authorize("viewAny", NUR2G::class);

        $allTickets = CairoYearlyStatestics::allYearTickets($year);
        $cairoAllTickets=$allTickets->where("work_group","HLGAM");
        $NUR_Power_yearly = CairoYearlyStatestics::cairoPowerNUR($cairoAllTickets, $year);
        return response()->json([
            "NUR_C_yearly" => $NUR_Power_yearly
        ], 200);
    }

    public function cairoNodeBYearlyNUR($year)
    {
        $this->authorize("viewAny", NUR2G::class);


        $allTickets = CairoYearlyStatestics::allYearTickets($year);
        $cairoAllTickets=$allTickets->where("work_group","HLGAM");
        $NUR_NodeB_yearly = CairoYearlyStatestics::cairoNodeBNUR($cairoAllTickets, $year);
        return response()->json([
            "NUR_C_yearly" => $NUR_NodeB_yearly
        ], 200);
    }
    public function cairoMWYearlyNUR($year)
    {
        $this->authorize("viewAny", NUR2G::class);

        $allTickets = CairoYearlyStatestics::allYearTickets($year);
        $cairoAllTickets=$allTickets->where("work_group","HLGAM");
        $NUR_TX_yearly = CairoYearlyStatestics::cairoTXNUR($cairoAllTickets, $year);
        return response()->json([
            "NUR_C_yearly" =>  $NUR_TX_yearly
        ], 200);
    }
    public function cairoGenYearlyNUR($year)
    {
        $this->authorize("viewAny", NUR2G::class);

        $allTickets = CairoYearlyStatestics::allYearTickets($year);
        $cairoAllTickets=$allTickets->where("work_group","HLGAM");
        $NUR_Gen_yearly = CairoYearlyStatestics::cairoGenNUR($cairoAllTickets, $year);
        return response()->json([
            "NUR_C_yearly" =>  $NUR_Gen_yearly
        ], 200);
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////////







///////////////////////////////////////Weekly Network NUR//////////////////////////////////////////////


    public function cairoModificationWeeklyNUR($week, $year)
    {
        $this->authorize("viewAny", NUR2G::class);
        $data = [

            "week" => $week,
            "year" => $year
        ];
        $validator = Validator::make($data, ["week" => ["required", 'integer', "between:1,52"], "year" => ['required', 'regex:/^2[0-9]{3}$/']]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        } else {
            $validated = $validator->validated();
            $total_week = $this->getCairoWeekTechTickets($validated["week"], $validated['year'],"NUR");
            $allTickets = $this->collectAllWeekTickets($total_week['tickets_2G'], $total_week['tickets_3G'], $total_week['tickets_4G']);

            $weeklyStatestics = CairoWeeklyStatestics::cairoModificationStatestics($allTickets);


            return response()->json([
                "statestics" => $weeklyStatestics['statestics'],
                "tickets" => $allTickets->where("type", "Voluntary")->values(),
                "sites" => $weeklyStatestics['impactedSites']
            ], 200);
        }
    }

    public function cairoPowerWeeklyNUR($week, $year)
    {
        $this->authorize("viewAny", NUR2G::class);
        $data = [

            "week" => $week,
            "year" => $year
        ];
        $validator = Validator::make($data, ["week" => ["required", 'integer', "between:1,52"], "year" => ['required', 'regex:/^2[0-9]{3}$/']]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        } else {
            $validated = $validator->validated();
            $total_week = $this->getCairoWeekTechTickets($validated["week"], $validated['year'],"NUR");
            $allTickets = $this->collectAllWeekTickets($total_week['tickets_2G'], $total_week['tickets_3G'], $total_week['tickets_4G']);
            $weeklyStatestics = CairoWeeklyStatestics::cairoMainPowerStatestics($allTickets);


            return response()->json([
                "statestics" => $weeklyStatestics['statestics'],
                "tickets" => $allTickets->where("sub_system", "MAIN POWER")->values(),
                "sites" => $weeklyStatestics['impactedSites']
            ], 200);
        }
    }


    public function cairoMWweeklyNUR($week, $year)
    {
        $this->authorize("viewAny", NUR2G::class);
        $data = [

            "week" => $week,
            "year" => $year
        ];
        $validator = Validator::make($data, ["week" => ["required", 'integer', "between:1,52"], "year" => ['required', 'regex:/^2[0-9]{3}$/']]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        } else {
            $validated = $validator->validated();
            $total_week = $this->getCairoWeekTechTickets($validated["week"], $validated['year'],"NUR");
            $allTickets = $this->collectAllWeekTickets($total_week['tickets_2G'], $total_week['tickets_3G'], $total_week['tickets_4G']);
            $weeklyStatestics = CairoWeeklyStatestics::cairoMWStatestics($allTickets);


            return response()->json([
                "statestics" => $weeklyStatestics['statestics'],
                "tickets" => $allTickets->where("system", "transmission")->values(),
                "sites" => $weeklyStatestics['impactedSites']
            ], 200);
        }
    }



    public function cairoGenweeklyNUR($week, $year)
    {
        $this->authorize("viewAny", NUR2G::class);
        $data = [

            "week" => $week,
            "year" => $year
        ];
        $validator = Validator::make($data, ["week" => ["required", 'integer', "between:1,52"], "year" => ['required', 'regex:/^2[0-9]{3}$/']]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        } else {
            $validated = $validator->validated();
            $total_week = $this->getCairoWeekTechTickets($validated["week"], $validated['year'],"NUR");
            $allTickets = $this->collectAllWeekTickets($total_week['tickets_2G'], $total_week['tickets_3G'], $total_week['tickets_4G']);

            $weeklyStatestics = CairoWeeklyStatestics::cairoGenStatestics($allTickets);

            return response()->json([
                "statestics" => $weeklyStatestics['statestics'],
                "tickets" => $allTickets->where("sub_system", "GENERATOR")->values(),
                "sites" => $weeklyStatestics['impactedSites']
            ], 200);
        }
    }


    public function cairoNodeBWeeklyNUR($week, $year)
    {
        $this->authorize("viewAny", NUR2G::class);
        $data = [

            "week" => $week,
            "year" => $year
        ];
        $validator = Validator::make($data, ["week" => ["required", 'integer', "between:1,52"], "year" => ['required', 'regex:/^2[0-9]{3}$/']]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        } else {
            $validated = $validator->validated();
            $total_week = $this->getCairoWeekTechTickets($validated["week"], $validated['year'],"NUR");
            $allTickets = $this->collectAllWeekTickets($total_week['tickets_2G'], $total_week['tickets_3G'], $total_week['tickets_4G']);

            $weeklyStatestics = CairoWeeklyStatestics::cairoNodeBStatestics($allTickets);

            return response()->json([
                "statestics" => $weeklyStatestics['statestics'],
                "tickets" => $weeklyStatestics['tickets'],
                "sites" => $weeklyStatestics['impactedSites']
            ], 200);
        }
    }


    // private function cairoSubsystemNUR($week,$year)
    // {
    //     $total_week = $this->getWeekTechTickets($week, $year,"NUR");
    //     $allTickets = $this->collectAllWeekTickets($total_week['tickets_2G'], $total_week['tickets_3G'], $total_week['tickets_4G']);
    //     $weeklyStatestics = 

    //     return $weeklyStatestics;

    // }
}
