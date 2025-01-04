<?php

namespace App\Services\EnergyAlarms;
use App\Services\EnergyAlarms\HTAlarmsHelpers;
use App\Services\EnergyAlarms\GenAlarmsHelpers;
use App\Services\EnergyAlarms\DownAlarmsHelpers;
use App\Services\EnergyAlarms\PowerAlarmsHelpers;


class WeeklyStatestics
{
    protected $powerAlarms, $genAlarms, $HTAlarms, $downAlarms;
    public function __construct($powerAlarms, $genAlarms, $HTAlarms, $downAlarms)
    {
        $this->powerAlarms = $powerAlarms;
        $this->genAlarms = $genAlarms;
        $this->HTAlarms = $HTAlarms;
        $this->downAlarms = $downAlarms;
       
    }

    public function zonesPowerAlarmsCount()
    {

        $powerAlarmsStatestics = new PowerAlarmsHelpers($this->powerAlarms);

        $zonesPowerAlarmsCount = $powerAlarmsStatestics->zonesPowerAlarmsCount($this->powerAlarms->groupBy('operational_zone')->keys());

        return  $zonesPowerAlarmsCount;
    }
    public function zonesGenAlarmsCount()
    {

        $genAlarmsStatestics = new GenAlarmsHelpers($this->genAlarms);

        $zonesGenAlarmsCount = $genAlarmsStatestics->zonesGenAlarmsCount($this->genAlarms->groupBy('operational_zone')->keys());

        return  $zonesGenAlarmsCount;
    }

    public function zonesHTAlarmsCount()
    {
        $HTAlarmsStatestics=new HTAlarmsHelpers($this->HTAlarms);
         $zonesHTAlarmsCount=$HTAlarmsStatestics->zonesHTAlarmsCount($this->HTAlarms->groupBy('operational_zone')->keys());
         return $zonesHTAlarmsCount;
    }

    public function zonesDownAlarmsCount()
    {
        $downAlarmsStatestics=new DownAlarmsHelpers($this->downAlarms);
        $zonesDownAlarmsCount=$downAlarmsStatestics->zonesDownAlarmsCount($this->downAlarms->groupBy('operational_zone')->keys());
        return  $zonesDownAlarmsCount;

    }

    public function  zonesNumberSitesReporteHTdAlarms()
    {
        $HTAlarmsStatestics=new HTAlarmsHelpers($this->HTAlarms);
         $zonesGenAlarmsNumberSites=$HTAlarmsStatestics->zonesNumberSitesReportedAlarms($this->HTAlarms->groupBy('operational_zone')->keys());
         return  $zonesGenAlarmsNumberSites;

    }
    public function  zonesNumberSitesReporteGendAlarms()
    {
        $HTAlarmsStatestics=new GenAlarmsHelpers($this->genAlarms);
         $zonesGenAlarmsNumberSites=$HTAlarmsStatestics->zonesNumberSitesReportedAlarms($this->genAlarms->groupBy('operational_zone')->keys());
         return  $zonesGenAlarmsNumberSites;

    }





    public function zonesSitesReportedPowerAlarms()
    {
        $powerAlarmsStatestics = new PowerAlarmsHelpers($this->powerAlarms);

        $zonesSitesReportedPowerAlarms = $powerAlarmsStatestics->zonesSitesReportedAlarms($this->powerAlarms->groupBy("operational_zone")->keys());

        return  $zonesSitesReportedPowerAlarms;
    }

    public function zonesSitesReportedGenAlarms()
    {
        $genAlarmsStatestics = new GenAlarmsHelpers($this->genAlarms);

        $zonesSitesReportedGenAlarms = $genAlarmsStatestics->zonesSitesReportedAlarms($this->genAlarms->groupBy("operational_zone")->keys());

        return  $zonesSitesReportedGenAlarms;
    }

    public function zonesSitesReportedHTAlarms()
    {
          $HTAlarmsStatestics = new HTAlarmsHelpers($this->HTAlarms);

        $zonesSitesReportedHTAlarms =$HTAlarmsStatestics->zonesSitesReportedAlarms($this->HTAlarms->groupBy("operational_zone")->keys());

        return   $zonesSitesReportedHTAlarms;
    }

   
   


}
