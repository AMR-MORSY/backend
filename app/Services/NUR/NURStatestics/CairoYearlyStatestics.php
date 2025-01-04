<?php

namespace App\Services\NUR\NURStatestics;

use App\Models\NUR\NUR2G;
use App\Models\NUR\NUR3G;
use App\Models\NUR\NUR4G;

class CairoYearlyStatestics
{

    public static function cairoNUR_C($allTickets,$year)
    {
       
        $cairoNUR_C = CairoNURHelpers::getNUR_C($allTickets);
       
        $zonesNUR_c = CairoNURHelpers::zonesNUR_C($allTickets,$year);

        return [
            "cairo" => $cairoNUR_C,
            "zones" => $zonesNUR_c
        ];
    }


    public static function cairoTxNUR($allTickets,$year)
    {
        $TXTickets=$allTickets->where("system", "transmission")->values();
        $cairoNUR_C = CairoNURHelpers::getNUR_C( $TXTickets);
        $zonesNUR_c =  CairoNURHelpers::zonesNUR_C( $TXTickets,$year);
        return [
            "cairo" =>  $cairoNUR_C,
            "zones" => $zonesNUR_c
        ];
    }
    public static function cairoModificationNUR($allTickets,$year)
    {
        $modificationsTickets=$allTickets->where('type',"Voluntary")->values();
        $cairoNUR_C =CairoNURHelpers::getNUR_C( $modificationsTickets);
        $zonesNUR_c = CairoNURHelpers::zonesNUR_C( $modificationsTickets,$year);

        return [
            "cairo" => $cairoNUR_C,
            "zones" => $zonesNUR_c
        ];
    }
    public static function cairoPowerNUR($allTickets,$year)
    {
        
        $powerTickets=$allTickets->where("sub_system", "MAIN POWER")->values();
        $cairoNUR_C =CairoNURHelpers::getNUR_C( $powerTickets);
        $zonesNUR_c = CairoNURHelpers::zonesNUR_C( $powerTickets,$year);

        return [
            "cairo" => $cairoNUR_C,
            "zones" => $zonesNUR_c
        ];
    }
    public static function cairoNodeBNUR($allTickets,$year)
    {
        $allNodeBTickets=CairoNURHelpers::collectingNodeBTickest($allTickets);
      

        $cairoNUR_C = CairoNURHelpers::getNUR_C($allNodeBTickets);
        $zonesNUR_c = CairoNURHelpers::zonesNUR_C($allNodeBTickets,$year);

        return [
            "cairo" => $cairoNUR_C,
            "zones" => $zonesNUR_c
        ];
    }

    public static function cairoGenNUR($allTickets,$year)
    {
        $allGenTickets=$allTickets->where('sub_system',"GENERATOR")->values();
        $allTickets_week =  $allGenTickets->sortBy([['week', 'asc']])->groupBy("week");
        $NUR_week_c = [];
        foreach ($allTickets_week as $week => $tickets) {

            $statestics["NUR_combined"] = number_format($tickets->sum('nur_c'), 2, '.', ',');
            $statestics["NUR_Org_combined"] = number_format($tickets->where('gen_owner', 'orange')->sum('nur_c'), 2, '.', ',');
            $statestics["NUR_Rented_combined"] =number_format( $tickets->where('gen_owner', 'rented')->sum('nur_c'), 2, '.', ',');
            $statestics["NUR_ET_combined"] =number_format($tickets->where("gen_owner", "shared")->where("Action_OGS_responsible", "ND(Etisalat)")->sum('nur_c'), 2, '.', ',');
            $statestics["NUR_VF_combined"] =number_format( $tickets->where("gen_owner", "shared")->where("Action_OGS_responsible", "ND(VF)")->sum('nur_c'), 2, '.', ',');
            $statestics["NUR_WE_combined"] =number_format($tickets->where("gen_owner", "shared")->where("Action_OGS_responsible", "ND(WE)")->sum('nur_c'), 2, '.', ',');

            $NUR_week_c["week $week"] = $statestics;
        }
        return $NUR_week_c;


    }
   

    public static function allYearTickets($year)
    {
        $allTickets = [];
        $tickets_2G =  NUR2G::where('year', $year)->get();
        $tickets_3G = NUR3G::where('year', $year)->get();
        $tickets_4G = NUR4G::where('year', $year)->get();
        foreach( $tickets_2G as $NUR)
        {
            array_push($allTickets,$NUR);
        }
        foreach($tickets_3G as $NUR)
        {
            array_push($allTickets,$NUR);
        }
        foreach($tickets_4G as $NUR)
        {
            array_push($allTickets,$NUR);
        }
        $allTickets = collect($allTickets);

        return $allTickets;
    }
}
