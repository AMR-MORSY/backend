<?php

namespace App\Services\NUR\NURStatestics;

use App\Models\NUR\NUR3G;

class CairoWeeklyStatestics
{

    public static function cairoGenStatestics($allTickets)
    {

        $statestics = [];

        $allGenTickets = $allTickets->where("sub_system", "GENERATOR")->values();
        $combined = $allGenTickets->sum('nur_c');
        $NUR_combined = number_format($combined, 2, '.', ',');

        ///////////////////////////////////OEG////////////////////////////////////////////
        $ORG = $allTickets->where("gen_owner", "orange")->values(); //////Orange generator tickets
        $combined = $ORG->sum('nur_c');
        $NUR_Org_combined = number_format($combined, 2, '.', ',');

        /////////////////////////////////Rented/////////////////////////////////////////
        $Rented = $allTickets->where("gen_owner", "rented")->values();
        $combined =  $Rented->sum('nur_c');
        $NUR_Rented_combined = number_format($combined, 2, '.', ',');

        /////////////////////////////////VF/////////////////////////////////////////
        $VFTickets = $allTickets->where("gen_owner", "shared")->where("Action_OGS_responsible", "ND(VF)")->values();
        $NUR_c = $VFTickets->sum('nur_c');
        $NUR_VF_combined = number_format($NUR_c, 2, '.', ',');

        ///////////////////////////////////////ET////////////////////////////////////
        $ETTickets = $allTickets->where("gen_owner", "shared")->where("Action_OGS_responsible", "ND(Etisalat)")->values();
        $NUR_c =  $ETTickets->sum('nur_c');
        $NUR_ET_combined = number_format($NUR_c, 2, '.', ',');
        //////////////////////////////////WE///////////////////////////////////////////
        $WETickets = $allTickets->where("gen_owner", "shared")->where("Action_OGS_responsible", "ND(WE)")->values();
        $NUR_c = $WETickets->sum('nur_c');
        $NUR_WE_combined = number_format($NUR_c, 2, '.', ',');



        $statestics["NUR_combined"] = $NUR_combined;
        $statestics["NUR_Org_combined"] = $NUR_Org_combined;
        $statestics["NUR_Rented_combined"] = $NUR_Rented_combined;
        $statestics["NUR_ET_combined"] = $NUR_ET_combined;
        $statestics["NUR_VF_combined"] = $NUR_VF_combined;
        $statestics["NUR_WE_combined"] = $NUR_WE_combined;


        $impactedSites = CairoNURHelpers::getImpactedSites($allGenTickets);

        $data['statestics'] = $statestics;
        $data['impactedSites'] = $impactedSites;

        return $data;
    }

    public static function cairoMainPowerStatestics($allTickets)
    {
        $statestics = [];
        $allMainPowerTickets = $allTickets->where("sub_system", "MAIN POWER")->values();
        $NUR_c = $allMainPowerTickets->sum('nur_c');
        $NUR_combined = number_format($NUR_c, 2, '.', ',');
        $allWithoutAccessMainPowerTickets = $allTickets->where("sub_system", "MAIN POWER")->where("access", 0)->values();
        $NUR_c = $allWithoutAccessMainPowerTickets->sum('nur_c');
        $NUR_without_access_combined = number_format($NUR_c, 2, '.', ',');
        $allWithAccessMainPowerTickets = $allTickets->where("sub_system", "MAIN POWER")->where("access", 1)->values();
        $NUR_c = $allWithAccessMainPowerTickets->sum('nur_c');
        $NUR_access_combined = number_format($NUR_c, 2, '.', ',');


        $statestics["NUR_access_c"] = $NUR_access_combined;
        $statestics["NUR_without_access_c"] = $NUR_without_access_combined;
        $statestics["NUR_combined"] = $NUR_combined;

        $impactedSites = CairoNURHelpers::getImpactedSites($allMainPowerTickets);


        $data["statestics"] = $statestics;
        $data['impactedSites'] = $impactedSites;


        return $data;
    }

    public static function cairoModificationStatestics($allTickets)
    {
        $statestics = [];
        $allMainPowerTickets = $allTickets->where("type", "Voluntary")->values();
        $NUR_c = $allMainPowerTickets->sum('nur_c');
        $NUR_combined = number_format($NUR_c, 2, '.', ',');
        $allWithoutAccessMainPowerTickets = $allTickets->where("type", "Voluntary")->where("access", 0)->values();
        $NUR_c = $allWithoutAccessMainPowerTickets->sum('nur_c');
        $NUR_without_access_combined = number_format($NUR_c, 2, '.', ',');
        $allWithAccessMainPowerTickets = $allTickets->where("type", "Voluntary")->where("access", 1)->values();
        $NUR_c = $allWithAccessMainPowerTickets->sum('nur_c');
        $NUR_access_combined = number_format($NUR_c, 2, '.', ',');


        $statestics["NUR_access_c"] = $NUR_access_combined;
        $statestics["NUR_without_access_c"] = $NUR_without_access_combined;
        $statestics["NUR_combined"] = $NUR_combined;

        $impactedSites = CairoNURHelpers::getImpactedSites($allMainPowerTickets);


        $data["statestics"] = $statestics;
        $data['impactedSites'] = $impactedSites;


        return $data;
    }

    public static function cairoMWStatestics($allTickets)
    {
        $statestics = [];

        $allMWTickets = $allTickets->where("system", "transmission")->values();
        $combined = $allMWTickets->sum('nur_c');
        $NUR_combined = number_format($combined, 2, '.', ',');

        $allVolantMWTickets = $allTickets->where("system", "transmission")->where("type", "Voluntary")->values();
        $combined = $allVolantMWTickets->sum('nur_c');
        $NUR_voluntary_combined = number_format($combined, 2, '.', ',');

        $allHDSLTickets = $allTickets->where("system", "transmission")->whereStrict("sub_system", "HDSL")->values();
        $combined = $allHDSLTickets->sum('nur_c');
        $NUR_HDSL_combined = number_format($combined, 2, '.', ',');

        $allINvolantTickets = $allTickets->where("system", "transmission")->where("type", "Involuntary")->values();
        $combined = $allINvolantTickets->sum('nur_c');
        $NUR_involuntary_combined = number_format($combined, 2, '.', ',');

        $allAccessTickets = $allTickets->where("system", "transmission")->where("access", 1)->values();
        $combined = $allAccessTickets->sum('nur_c');
        $NUR_access_combined = number_format($combined, 2, '.', ',');


        $allWithoutAccessTickets = $allTickets->where("system", "transmission")->where("access", 0)->values();
        $combined = $allWithoutAccessTickets->sum('nur_c');
        $NUR_without_access_combined = number_format($combined, 2, '.', ',');

        $statestics["NUR_combined"] = $NUR_combined;
        $statestics["NUR_voluntary_c"] = $NUR_voluntary_combined;
        $statestics["NUR_involuntary_c"] = $NUR_involuntary_combined;
        $statestics["NUR_access_c"] = $NUR_access_combined;
        $statestics["NUR_without_access_c"] = $NUR_without_access_combined;
        $statestics["NUR_HDSL_c"] = $NUR_HDSL_combined;

        $impactedSites = CairoNURHelpers::getImpactedSites($allMWTickets);


        $data["statestics"] =  $statestics;
        $data['impactedSites'] = $impactedSites;


        return $data;
    }

    public static function cairoNodeBStatestics($allTickets)
    {

        $statestics = [];
       
        $allNodeBTickets=CairoNURHelpers::collectingNodeBTickest($allTickets);
        $NUR_c =  $allNodeBTickets->sum('nur_c');
        $NUR_combined = number_format($NUR_c, 2, '.', ',');

        $allWithoutAccessNodeBTickets =  $allNodeBTickets->where("access", 0)->values();
        $NUR_c = $allWithoutAccessNodeBTickets->sum('nur_c');
        $NUR_without_access_combined = number_format($NUR_c, 2, '.', ',');
        $allWithAccessNodeBTickets =  $allNodeBTickets->where("access", 1)->values();
        $NUR_c = $allWithAccessNodeBTickets->sum('nur_c');
        $NUR_access_combined = number_format($NUR_c, 2, '.', ',');


        $statestics["NUR_access_c"] = $NUR_access_combined;
        $statestics["NUR_without_access_c"] = $NUR_without_access_combined;
        $statestics["NUR_combined"] = $NUR_combined;

        $impactedSites = CairoNURHelpers::getImpactedSites($allNodeBTickets);

        


        $data["statestics"] = $statestics;
        $data['impactedSites'] = $impactedSites;
        $data['tickets']=$allNodeBTickets;


        return $data;
    }

    public static function cairoSubsystem($allTickets)
    {
        $subs=[];
        $subsystems = $allTickets->groupBy("sub_system")->keys();
        foreach ($subsystems as $system) {
            $subSysTickets = $allTickets->whereStrict('sub_system', $system)->values();
            if(count($subSysTickets)>0)
            {
                $statestics=[];
                $NUR_c=number_format($subSysTickets->sum('nur_c'), 2, '.', ',');
                $countTick=$subSysTickets->count();
                $statestics['NUR']=$NUR_c;
                $statestics['countTick']=$countTick;
                $subs[$system] = $statestics;
                
            }
          
        }
        return $subs;
    }

    public static function cairoTopRepeated($allTickets)
    {
        $siteCodes = $allTickets->where('technology',"3G")->groupBy("problem_site_code");
        $statestics=[];
        foreach($siteCodes as $code=>$codeTickets)
        {
           
            $siteName=$codeTickets->first()->problem_site_name;
            $site["site_code"]=$code; ///////////site object
            $site['site_name']=$siteName;
            $site['count']=$codeTickets->count();
            $site['week']=$codeTickets->first()->week;
            $site['year']=$codeTickets->first()->year;
          
            array_push($statestics,$site);

           
        }
        $statestics=collect($statestics);
        $statestics=$statestics->sortByDesc("count");
        $statestics=$statestics->take(20);

        $sites=[];

        foreach($statestics as $site)
        {
            $pastWeekTickets=NUR3G::where('problem_site_code',$site['site_code'])->where('week',$site["week"]-1)->where('year',$site["year"])->get();
            $pastweekTicketsCount=0;
            if(count($pastWeekTickets)>0)
            {
                $pastweekTicketsCount=count($pastWeekTickets);
            }
            $sub["week".$site['week']-1]=$pastweekTicketsCount;
            $sub["week".$site['week']]=$site["count"];
            $sites[$site['site_code']."-".$site['site_name']]=$sub;

        }
        return $sites;


    }

    public static function workGroupFMComparison($allWeekFMTickest)
    {
        $allWeekFMTickest=collect($allWeekFMTickest);
        $AGLITicktes=$allWeekFMTickest->where('work_group',"ALGAM");
        $HGLITicktes=$allWeekFMTickest->where('work_group',"HLGAM");
        $NGLITicktes=$allWeekFMTickest->where('work_group',"NLGAM");
        $FM["HGLI"]["ticktes_count"]=$HGLITicktes->count();
        $FM["HGLI"]["NUR"]=number_format($HGLITicktes->sum('nur_c'), 2, '.', ','); 
        $FM["NGLI"]["ticktes_count"]=$NGLITicktes->count();
        $FM["NGLI"]["NUR"]=number_format($NGLITicktes->sum('nur_c'), 2, '.', ','); 
        $FM["AGLI"]["ticktes_count"]=$AGLITicktes->count();
        $FM["AGLI"]["NUR"]=number_format($AGLITicktes->sum('nur_c'), 2, '.', ','); 
        $FM["total"]["ticktes_count"]=$FM["HGLI"]["ticktes_count"]+ $FM["NGLI"]["ticktes_count"]+  $FM["AGLI"]["ticktes_count"];
        $FM["total"]["NUR"]= $FM["HGLI"]["NUR"]+ $FM["AGLI"]["NUR"]+$FM["NGLI"]["NUR"];
        return $FM;

    }
}
