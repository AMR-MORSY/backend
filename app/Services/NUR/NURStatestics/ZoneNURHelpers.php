<?php

namespace App\Services\NUR\NURStatestics;



class ZoneNURHelpers
{


    public static function zonesTotalNumTickets($zones,$NUR_tickets)
    {
        $oz = [];
        foreach ($zones as $zone) {

            $oz[$zone] = $NUR_tickets->where("oz", $zone)->count();
        }
        return $oz;
    }


    public static function zonesNUR($zones, $period,$NUR_tickets)
    {
        $oz = [];
        foreach ($zones as $zone) {
            $data =$NUR_tickets->where("oz", $zone)->sum($period);
            $oz[$zone] = number_format($data, 2, '.', ',');
        }
        return $oz;
    }

    public static function zonesSubsystemNUR($zones,$allTickets)
    {
        $oz = [];
        $subs = [];
        foreach ($zones as $zone) {
            $subsystems = $allTickets->groupBy("sub_system")->keys();
            foreach ($subsystems as $system) {
                $zoneSubSysTickets = $allTickets->whereStrict("oz", $zone)->whereStrict('sub_system', $system)->values();
                if(count($zoneSubSysTickets)>0)
                {
                    $NUR_c=number_format($zoneSubSysTickets->sum('nur_c'), 2, '.', ',');
                    $subs[$system] = number_format($NUR_c, 2, '.', ',');
                }
              
            }
       
            $oz[$zone] = $subs;
        }



        return $oz;
    }

    public static function zonesSubsystemCountTickts($zones,$allTickets)
    {
        $oz = [];
        $subs = [];
        foreach ($zones as $zone) {
            $subsystems = $allTickets->groupBy("sub_system")->keys();
            foreach ($subsystems as $system) {
                $zoneSubSysTickets=$allTickets->where("oz", $zone)->where('sub_system', $system)->values();
                if(count($zoneSubSysTickets)>0)
                {
                    $subs[$system] = $zoneSubSysTickets->count();
                }
            
            }
        
            $oz[$zone] = $subs;
        }
        return $oz;
    }

    public static function zonesRepeatedSites($zones,$allTickets)
    {
        $oz = [];


        foreach ($zones as $zone) {
            $subs = [];
        
            $siteCodes = $allTickets->where("oz", $zone)->where('technology',"3G")->groupBy("problem_site_code");
            foreach ($siteCodes as $key => $codes) {



                $site["siteName"] = $codes->first()->problem_site_name;
                $site["siteCode"] = $codes->first()->problem_site_code;
                $site["count"] = $codes->count();
                array_push($subs, $site);
            }

            $sub = collect($subs);

            $sub = $sub->sortByDesc("count");
            $sub = $sub->take(7);
            $oz[$zone] = $sub;
        }
        return $oz;
    }

   

    public static function zonesTopSitesNUR($zones, $allTickets)
    {
        $oz = [];



        foreach ($zones as $zone) {
            $subs = [];
            $site = [];
            $siteCodes = $allTickets->where("oz", $zone)->groupBy("problem_site_code");


            foreach ($siteCodes as $key => $codes) {
                $site["siteName"] = $codes->first()->problem_site_name;
                $site["siteCode"] = $codes->first()->problem_site_code;
            
                $NUR_c=$codes->sum('nur_c');

                $site["NUR"] = number_format($NUR_c, 2, '.', ',');
                array_push($subs, $site);
            }

            $sub = collect($subs);

            $sub = $sub->sortByDesc("NUR");
            $sub = $sub->take(7);
            $oz[$zone] = $sub->values();
        }
        return $oz;
    }


    public static function zonesResponseWithAccess($zones,$allTickets)
    {
        $oz = [];
        $subs = [];
        foreach ($zones as $zone) {

            $zoneAccessTickets = $allTickets->Where("oz", $zone)->where("access", 1)->where('Dur_min', ">", 240)->values();
          
            $NUR_c=$zoneAccessTickets->sum('nur_c');
           

            $subs['exceedSLA'] = number_format($NUR_c, 2, '.', ',');
        
            $subs['exceedSLA_count_tickets'] = $zoneAccessTickets->count();
            $zoneWithinSLATickets = $allTickets->Where("oz", $zone)->where("access", 1)->where('Dur_min', "<=", 240)->values();
           
          
            $NUR_c= $zoneWithinSLATickets->sum('nur_c');
            $subs['withinSLA'] = number_format($NUR_c, 2, ".", ',');
          
            $subs['withinSLA_count_tickets'] = $zoneWithinSLATickets->count();

            $oz[$zone] = $subs;
        }

        return $oz;
    }
    public static function zonesResponseWithoutAccess($zones, $allTickets)
    {
        $oz = [];
        $subs = [];
        foreach ($zones as $zone) {
            $zoneExceedTickets= $allTickets->Where("oz", $zone)->where("access", 0)->where('Dur_min', ">", 240)->values();

          
            $NUR_c=$zoneExceedTickets->sum('nur_c');
            $subs['exceedSLA'] = number_format($NUR_c, 2, '.', ',');
       
            $subs['exceedSLA_count_tickets'] = $zoneExceedTickets->count();
            $zoneWithINTickets=$allTickets->Where("oz", $zone)->where("access", 0)->where('Dur_min', "<=", 240)->values();
          
       
            $NUR_c=$zoneWithINTickets->sum('nur_c');
            $subs['withinSLA'] = number_format($NUR_c, 2, ".", ',');
      
            $subs['withinSLA_count_tickets'] = $zoneWithINTickets->count();


            $oz[$zone] = $subs;
        }

        return $oz;
    }

   



    public static function zonesGeneratorStatestics($zones, $allTickets)
    {
        $oz = [];
        $subs = [];

        foreach ($zones as $zone) {
          
            //////////////////VF//////////////////////////////////////////////
            $VFTickets = $allTickets->where("oz", $zone)->where("gen_owner", "shared")->where("Action_OGS_responsible","ND(VF)")->values();
            $NUR_c = $VFTickets->sum('nur_c');
            $VF["nur"] = number_format($NUR_c, 2, '.', ',');
            $VF['count'] = $VFTickets->count();
            $VF['tickets']=$VFTickets;
            ///////////////////////////////////////ET////////////////////////////////////
            $ETTickets = $allTickets->where("oz", $zone)->where("gen_owner", "shared")->where("Action_OGS_responsible","ND(Etisalat)")->values();
            $NUR_c =  $ETTickets->sum('nur_c');
            $ET['nur'] = number_format($NUR_c, 2, '.', ',');
            $ET["count"] = $ETTickets->count();
            $ET['tickets']=$ETTickets;
            //////////////////////////////////////////WE////////////////////////////////////

            $WETickets = $allTickets->where("oz", $zone)->where("gen_owner", "shared")->where("Action_OGS_responsible","ND(WE)")->values();
            $NUR_c = $WETickets->sum('nur_c');
            $WE['nur'] = number_format($NUR_c, 2, '.', ',');
            $WE["count"] = $WETickets->count();
            $WE['tickets']=$WETickets;
            ////////////////////////////////////////OEG////////////////////////////////
            $ORG = $allTickets->where("oz", $zone)->where("gen_owner", "orange")->values();
            $NUR_c= $ORG->sum('nur_c');
            $oRG['count'] = $ORG->count();
            $oRG['nur'] = number_format($NUR_c, 2, '.', ',');
            $oRG["tickets"]=$ORG;
            ////////////////////////////////////Rented////////////////////////////////////
            $Rented = $allTickets->where("oz", $zone)->where("gen_owner", "rented")->values();  
            $NUR_c= $Rented->sum('nur_c');
            $rented['count'] = $Rented->count();
            $rented['nur'] = number_format(  $NUR_c, 2, '.', ',');  
            $rented["tickets"]=$Rented;
            ////////////////////////////////////////////////////////////////////////////////
            $subs['VF'] = $VF;
            $subs['ET'] = $ET;
            $subs['ORG'] = $oRG;
            $subs['Rented'] = $rented;
            $subs['WE'] = $WE;

            $oz[$zone] = $subs;
        }
        return $oz;
    }

   
}
