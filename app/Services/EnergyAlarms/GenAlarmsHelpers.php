<?php


namespace App\Services\EnergyAlarms;

class GenAlarmsHelpers{

    protected $GenAlarmsCollection;

    public function __construct($GenAlarms)
    {
        $this->GenAlarmsCollection=$GenAlarms;
      
        
    }

    public function zonesGenAlarmsCount($zones)
    {
        $oz = [];
        foreach ($zones as $zone) {
            $oz[$zone] = $this->GenAlarmsCollection->where("operational_zone", $zone)->count();
        }
        $totalAlarms=0;
        foreach($oz as $key=>$zone)
        {
            $totalAlarms=$totalAlarms+$zone;
        }
     
        $oz['Cairo']=$totalAlarms;

        return $oz;
    }
    public function zonesNumberSitesReportedAlarms($zones)
    {
        $oz = [];
        foreach ($zones as $zone) {
            $siteCodesCount = $this->GenAlarmsCollection->where("operational_zone", $zone)->groupBy("site_code")->keys()->count();
          
                   

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
    public function zonesSitesReportedAlarms($zones)
    {
        $oz = [];

        foreach ($zones as $zone) {
            $sites = [];
            $siteCodes = $this->GenAlarmsCollection->where("operational_zone", $zone)->groupBy("site_code");

            foreach ($siteCodes as $key => $codes) {

                $siteCode = $codes->sortByDesc("duration");
                $siteCode = $siteCode->first();
                $subs["siteName"] =  $siteCode->site_name;
                $subs["siteCode"] =  $siteCode->site_code;
                $subs["count"]=$codes->count();
                $subs["highest_duration"] = $this->convertMinutesToHours($siteCode->duration);
                array_push($sites, $subs);
            }
            $oz[$zone] =$sites;
        }


        return $oz;
    }
    private function convertMinutesToHours($minutes)
    {
        $quotient = (int)($minutes / 60);
        $remainder = $minutes % 60;
        return " $quotient:$remainder";
    }

    
}