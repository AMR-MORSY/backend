<?php

namespace App\Services\EnergyAlarms;

use Carbon\Carbon;
use App\Models\NUR\NUR2G;
use App\Models\NUR\NUR3G;


class PowerAlarmsHelpers
{

    protected $powerAlarmsCollection, $downAlarmsCollection;

    public function __construct($powerAlarms = null, $downAlarms = null)
    {
        $this->powerAlarmsCollection = $powerAlarms;
        $this->downAlarmsCollection = $downAlarms;
       
    }

    public function zonesPowerAlarmsCount($zones)
    {
        $oz = [];
        foreach ($zones as $zone) {
            $oz[$zone] = $this->powerAlarmsCollection->where("operational_zone", $zone)->count();
        }
        $totalAlarms=0;
        foreach($oz as $key=>$zone)
        {
            $totalAlarms=$totalAlarms+$zone;
        }
     
        $oz['Cairo']=$totalAlarms;


        return $oz;
    }


    public function zonesSitesReportedAlarms($zones)
    {
        $oz = [];
        foreach ($zones as $zone) {
            $siteCodesCount = $this->powerAlarmsCollection->where("operational_zone", $zone)->groupBy("site_code")->keys()->count();
          
                   

            $oz[$zone] = $siteCodesCount;
        }
        $total=0;
        foreach($oz as $key=>$value)
        {
            $total=$total+$value;
        }
        $oz['Cairo']=$total;
        return $oz;
    }
   

}
