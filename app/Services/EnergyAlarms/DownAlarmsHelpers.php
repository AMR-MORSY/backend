<?php

namespace App\Services\EnergyAlarms;

use App\Services\EnergyAlarms\HTAlarmsHelpers;
use Maatwebsite\Excel\Mixins\DownloadCollection;

class DownAlarmsHelpers
{
    protected $downAlarmsCollection;

    public function __construct($downAlarms)
    {
        $this->downAlarmsCollection = $downAlarms;
    }
    public function zonesDownAlarmsCount($zones)
    {
        $oz = [];
        foreach ($zones as $zone) {
            $oz[$zone] = $this->downAlarmsCollection->where("operational_zone", $zone)->count();
        }
        $totalAlarms = 0;
        foreach ($oz as $key => $zone) {
            $totalAlarms = $totalAlarms + $zone;
        }

        $oz['Cairo'] = $totalAlarms;

        return $oz;
    }
    public function zonesNumberSitesReportedAlarms($zones)
    {
        $oz = [];
        foreach ($zones as $zone) {
            $siteCodesCount = $this->downAlarmsCollection->where("operational_zone", $zone)->groupBy("site_code")->keys()->count();



            $oz[$zone] = $siteCodesCount;
        }
        $total = 0;
        foreach ($oz as $key => $value) {
            $total = $total + $value;
        }
        $oz['Cairo'] = $total;
        return $oz;
    }
    private function extractSites($groupedAlarms)
    {
        $sites = [];
        foreach ($groupedAlarms as $key => $alarm) {
            $site["site_code"] = $key;
            $site["site_name"] = $alarm->first()->site_name;
            $site["repeatation"] = $alarm->count();
            $site["avg_duration"] = HTAlarmsHelpers::convertMinutesToHours($alarm->avg('duration'));
            array_push($sites, $site);
        }
        return $sites;
    }
    public function zoneSitesReportedDownAlarms()
    {
        $alarmsGroupedByCodes2G = $this->downAlarmsCollection->where("alarm_name", "OML Fault")->groupBy("site_code");
        $alarmsGroupedByCodes3G = $this->downAlarmsCollection->where("alarm_name", "NodeB Unavailable")->groupBy("site_code");
        $sites2G = $this->extractSites($alarmsGroupedByCodes2G);
        $sites3G = $this->extractSites($alarmsGroupedByCodes3G);

        $allSites["down2G"] = $sites2G;
        $allSites["down3G"] = $sites3G;
        return $allSites;
    }

    private function calculateDuration($start, $end)

    {
        $result = intdiv((strtotime($start) - strtotime($end)), 60);
        if ($result < 0) {
            return $result + (24 * 60);
        } else {
            return $result;
        }
    }

    public function siteDownAlarmsGroupedByWeek()
    {
        if(count($this->downAlarmsCollection)>0)
        {
            $downAlarmsYears = $this->downAlarmsCollection->groupBy("year");
            foreach($downAlarmsYears as $year=>$alarms)
            {
               
                $downAlarmsGroupedByWeek=$alarms->sortBy([['week', 'asc']])->groupBy("week");
                $countAlarms=[];
                $avgDownTime=[];
                foreach($downAlarmsGroupedByWeek as $week=>$downAlarms)
                {
                    $alarm["week$week"]=$downAlarms->count();
                    $time["week$week"]=intval($downAlarms->avg('duration'));
                    $countAlarms=$alarm;
                    $avgDownTime=$time;
    
    
                }
                $downAlarmsCountYearsCollection[$year]=$countAlarms;
                $avgDownTimeYearsCollection[$year]=$avgDownTime;

            }
           
           
            return[
                "alarmsCount"=>$downAlarmsCountYearsCollection,
                "avgDownTime"=> $avgDownTimeYearsCollection,
                "alarms"=>"exist"

            ];

        }
        else{
            return [
                "alarms"=>"not exist"
            ];
        }

    }

    public function siteBatteriesHealth($powerAlarmsCollection)
    {
        if (count($powerAlarmsCollection) > 0) {
            $powerAlarmsYears = $powerAlarmsCollection->groupBy("year");
            foreach ($powerAlarmsYears as $year => $alarms) {

                $powerAlarmsWeek = $alarms->sortBy([['week', 'asc']])->groupBy("week");
                $powerAlarmBeforDownWeekCount=[];
                $avgBackuptimeWeek=[];
                $powerAlarmsWithoutDownCount=[];
                $avgAlarmsDurationWithoutDown=[];
                foreach ($powerAlarmsWeek as $key => $values) {
                   
                    $durationsBeforeDown = [];
                    $powerAlarmsDurs=[];
                    foreach ($values as $value) {
                        $downAlarm = $this->downAlarmsCollection->where("week", $key)->where("year", $year)->whereStrict("start_date", $value->start_date)->where("end_date", $value->end_date)->where("start_time", ">=", $value->start_time)->where("end_time", "<=", $value->end_time)->whereIn("alarm_name", ["NodeB Unavailable", "OML Fault"])->first();
                        if ($downAlarm) {
                            $duration["duration"] = $this->calculateDuration($downAlarm->start_time, $value->start_time);
                            array_push($durationsBeforeDown, $duration);
                            $powerAlarmBeforDownWeekCount["week$key"] = collect($durationsBeforeDown)->count();
                            $avgBackuptimeWeek["week$key"] =  intval(collect($durationsBeforeDown)->max("duration"));
        
                        } else {
                            $duration['duration'] = $value->duration;
                            array_push($powerAlarmsDurs, $duration);
                            $powerAlarmsWithoutDownCount["week$key"]=collect($powerAlarmsDurs)->count();
                            $avgAlarmsDurationWithoutDown["week$key"]= intval(collect($powerAlarmsDurs)->max("duration"));
                        }
                    }
                  
                   
                  
                    
                  
                }
              
              
                $countAlarmsYearsCollectionBeforeDown[$year] = $powerAlarmBeforDownWeekCount;
                $avgBackuptimeYearsCollection[$year] = $avgBackuptimeWeek;

                $powerAlarmsWithoutDownCountYearsCollection[$year]= $powerAlarmsWithoutDownCount;
                $avgAlarmsDurationWithoutDownYearsCollection[$year]= $avgAlarmsDurationWithoutDown;
               
            }



            return [
                "powerAlarms" => "exist",
                "powerAlarmsCountBeforeDown" =>  $countAlarmsYearsCollectionBeforeDown,
                "maxBackuptime" => $avgBackuptimeYearsCollection,
                "powerAlarmsWithoutDown"=>  $powerAlarmsWithoutDownCountYearsCollection,
                "maxAlarmsDurationWithoutDown"=>  $avgAlarmsDurationWithoutDownYearsCollection


            ];
        } else {
            return [
                "powerAlarms" => "dead batteries"

            ];
        }
    }

    public function zoneDownSitesAfterPowerAlarm($powerAlarmsCollection)
    {
        $sites = [];
        $siteCodes = $powerAlarmsCollection->groupBy("site_code");

        foreach ($siteCodes as $key => $codes) {
            foreach ($codes as $code) {
                $downAlarm = $this->downAlarmsCollection->where("site_code", $code->site_code)->whereStrict("start_date", $code->start_date)->where("end_date", $code->end_date)->where("start_time", ">=", $code->start_time)->where("end_time", "<=", $code->end_time)->whereIn("alarm_name", ["NodeB Unavailable", "OML Fault"])->first();
                if ($downAlarm) {
                    $alarm["site_name"] = $downAlarm->site_name;
                    $alarm["site_code"] = $downAlarm->site_code;
                    // $alarm["downAlarm_start_date"] = $downAlarm->start_date;
                    // $alarm["downAlarm_start_time"] = $downAlarm->start_time;
                    // $alarm["downAlarm_end_date"] = $downAlarm->end_date;
                    // $alarm["downAlarm_end_time"] = $downAlarm->end_time;
                    // $alarm["powerAlarm_start_date"] = $code->start_date;
                    // $alarm["powerAlarm_start_time"] = $code->start_time;
                    // $alarm["powerAlarm_end_date"] = $code->end_date;
                    // $alarm["powerAlarm_end_time"] = $code->end_time;
                    // $alarm["downAlarm_id"] = $downAlarm->id;
                    $alarm['durationBeforDown'] = $this->calculateDuration($downAlarm->start_time, $code->start_time);

                    // $alarm["downAlarmDuration"] =$this->calculateDuration($downAlarm->end_time,$downAlarm->start_time);

                    array_push($sites, $alarm);
                } 
                // else {
                //     $alarm["site_name"] = $code->site_name;
                //     $alarm["site_code"] = $code->site_code;
                //     // $alarm['durationBeforDown'] = $this->calculateDuration($code->start_time, $code->end_time);

                //     $alarm['durationBeforDown'] = intval($code->duration);
                //     array_push($sites, $alarm);
                // }
            }
        }
        $sites = collect($sites);
        $sitesCodes = $sites->groupBy("site_code");
        $newSites = [];
        foreach ($sitesCodes as $key => $codes) {
            $site["site_name"] = $codes->first()["site_name"];
            $site["site_code"] = $key;
            $site["avgBackuptime"] = intval($codes->avg("durationBeforDown"));
            array_push($newSites, $site);
        }

        return $newSites;
    }
    private function extractSitesDownWithoutPower($groupedAlarms, $powerAlarmsCollection)
    {
        $sites = [];
        foreach ($groupedAlarms as $key => $codes) {
            $powerAlarm = $powerAlarmsCollection->where("site_code", $key)->first();
            if (!$powerAlarm) {
                $alarm["site_name"] = $codes->first()["site_name"];
                $alarm["site_code"] = $key;
                $alarm["repeatation"] = $codes->count();
                $alarm["avg_duration"] = HTAlarmsHelpers::convertMinutesToHours($codes->avg("duration"));;
                array_push($sites, $alarm);
            }
        }
        return $sites;
    }
    public function zoneSitesDownWithoutPowerAlarms($powerAlarmsCollection)
    {
        $downAlarmsGroupedCodes2G = $this->downAlarmsCollection->where("alarm_name", "OML Fault")->groupBy("site_code");
        $downAlarmsGroupedCodes3G = $this->downAlarmsCollection->where("alarm_name", "NodeB Unavailable")->groupBy("site_code");

        $sites2G = $this->extractSitesDownWithoutPower($downAlarmsGroupedCodes2G, $powerAlarmsCollection);
        $sites3G = $this->extractSitesDownWithoutPower($downAlarmsGroupedCodes3G, $powerAlarmsCollection);
        $allSites["down2G"] = $sites2G;
        $allSites["down3G"] = $sites3G;



        return $allSites;
    }
}
