<?php

namespace App\Services\NUR\NURStatestics;

use App\Services\NUR\Durations;
use App\Services\NUR\NURStatestics\ZoneNURHelpers;

class ZoneWeeklyStatestics
{
   

    public static function zonesSubsystemNUR($allTickets)
    {

      
        $zonesSubsystemNUR = ZoneNURHelpers::zonesSubsystemNUR($allTickets->groupBy('oz')->keys(), $allTickets);
        return $zonesSubsystemNUR;
    }
    public static function zonesSubsystemCountTickts($allTickets)
    {
      
        $zonesSubsystemCountTickts = ZoneNURHelpers::zonesSubsystemCountTickts($allTickets->groupBy('oz')->keys(), $allTickets);
        return $zonesSubsystemCountTickts;
    }

    public static function zonesResponseWithAccess($allTickets)
    {
      
        $zonesResponseWithAccess = ZoneNURHelpers::zonesResponseWithAccess($allTickets->groupBy('oz')->keys(), $allTickets);
        return $zonesResponseWithAccess;
    }

    public static function zonesResponseWithoutAccess($allTickets)
    {
      
        $zonesResponseWithoutAccess = ZoneNURHelpers::zonesResponseWithoutAccess($allTickets->groupBy('oz')->keys(), $allTickets);
        return $zonesResponseWithoutAccess;
    }
    public static function zonesGeneratorStatestics($allTickets)
    {
      
        $zonesGeneratorStatestics = ZoneNURHelpers::zonesGeneratorStatestics($allTickets->groupBy('oz')->keys(), $allTickets);
        return $zonesGeneratorStatestics;
    }


    public static function NUR2GStatestics($total_week_2G_tickets) /////collection
    {
       
        $cairo2GNUR = number_format($total_week_2G_tickets->sum('nur'), 2, '.', ',');
        $zonesNUR = ZoneNURHelpers::zonesNUR($total_week_2G_tickets->groupBy('oz')->keys(), "nur", $total_week_2G_tickets); /////zones,period,tickets
        $zonesTotalNumTickets = ZoneNURHelpers::zonesTotalNumTickets($total_week_2G_tickets->groupBy('oz')->keys(), $total_week_2G_tickets); //////zones,tickets
        $NUR2G['cairoNUR2G'] = $cairo2GNUR;
        $NUR2G['zonesNUR2G'] = $zonesNUR;
        $NUR2G['zonesTotalNumTickets'] = $zonesTotalNumTickets;


        return    $NUR2G;
    }
    public static function NUR3GStatestics($total_week_3G_tickets)
    {
       
        $cairo3GNUR = number_format($total_week_3G_tickets->sum('nur'), 2, '.', ',');
        $zonesNUR = ZoneNURHelpers::zonesNUR($total_week_3G_tickets->groupBy('oz')->keys(), "nur", $total_week_3G_tickets); /////zones,period,tickets
        $zonesTotalNumTickets = ZoneNURHelpers::zonesTotalNumTickets($total_week_3G_tickets->groupBy('oz')->keys(), $total_week_3G_tickets); //////zones,tickets
        $NUR3G['cairoNUR3G'] = $cairo3GNUR;
        $NUR3G['zonesNUR3G'] = $zonesNUR;
        $NUR3G['zonesTotalNumTickets'] = $zonesTotalNumTickets;




        return   $NUR3G;
    }
    public static function NUR4GStatestics($total_week_4G_tickets)
    {
      
        $cairo4GNUR = number_format($total_week_4G_tickets->sum('nur'), 2, '.', ',');
        $zonesNUR = ZoneNURHelpers::zonesNUR($total_week_4G_tickets->groupBy('oz')->keys(), "nur", $total_week_4G_tickets); /////zones,period,tickets
        $zonesTotalNumTickets = ZoneNURHelpers::zonesTotalNumTickets($total_week_4G_tickets->groupBy('oz')->keys(), $total_week_4G_tickets); //////zones,tickets
        $NUR4G['cairoNUR4G'] = $cairo4GNUR;
        $NUR4G['zonesNUR4G'] = $zonesNUR;
        $NUR4G['zonesTotalNumTickets'] = $zonesTotalNumTickets;




        return   $NUR4G;
    }
   


    public static function zonesTopRepeated($allTickets)
    {
     
        $zonesRepeatedSites = ZoneNURHelpers::zonesRepeatedSites($allTickets->groupBy('oz')->keys(), $allTickets);
        return $zonesRepeatedSites;
    }

    public static function officesNUR($allTickets)
    {
        $officesTickets=$allTickets->groupBy('office');
        $offices=[];
        foreach($officesTickets as $office=>$tickets)
        {
           
            $offices[$office]['countTick']=$tickets->count();
            $offices[$office]['NUR']= number_format($tickets->sum('nur_c'), 2, '.', ','); 
            $offices[$office]['Avg_Dur']=intval($tickets->avg("Dur_min"));
        

        }
        return $offices;
     
       


    }

    public static function zonesTopNUR($allTickets)
    {
     
        $zonesTopSitesNUR = ZoneNURHelpers::zonesTopSitesNUR($allTickets->groupBy('oz')->keys(), $allTickets);
        return  $zonesTopSitesNUR;
    }

    public static function combinedNUR($allTickets)
    {
       
        $cairoEastTicK = $allTickets->where('oz', 'CAIRO EAST');
        $cairoEastCombined = number_format($cairoEastTicK->sum('nur_c'), 2, '.', ',');
        $cairoSouthTicK = $allTickets->where('oz', 'CAIRO SOUTH');
        $cairoSouthCombined = number_format($cairoSouthTicK->sum('nur_c'), 2, '.', ',');
        $cairoNorthTicK = $allTickets->where('oz', 'CAIRO NORTH');
        $cairoNorthCombined = number_format($cairoNorthTicK->sum('nur_c'), 2, '.', ',');
        $gizaTicK = $allTickets->where('oz', 'GIZA');
        $gizaCombined = number_format($gizaTicK->sum('nur_c'), 2, '.', ',');
        $combined['CAIRO EAST'] =  $cairoEastCombined;
        $combined['CAIRO SOUTH'] =  $cairoSouthCombined;
        $combined['CAIRO NORTH'] =  $cairoNorthCombined;
        $combined['GIZA'] =  $gizaCombined;
        $combined["cairo"] = number_format($cairoEastCombined + $cairoNorthCombined + $cairoSouthCombined + $gizaCombined, 2, '.', ',');
        return $combined;
    }
}
