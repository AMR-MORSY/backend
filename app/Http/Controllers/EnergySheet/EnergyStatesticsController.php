<?php

namespace App\Http\Controllers\EnergySheet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EnergySheet\GenAlarm;
use App\Models\EnergySheet\DownAlarm;
use App\Models\EnergySheet\PowerAlarm;
use App\Models\EnergySheet\HighTempAlarm;
use Illuminate\Support\Facades\Validator;
use App\Services\EnergyAlarms\WeeklyStatestics;


class EnergyStatesticsController extends Controller
{
    public function statestics($week,$year)
    {
        $this->authorize("viewAny",DownAlarm::class);
        $data=[
           
            "week"=>$week,
          
            "year"=>$year
        ];
     
            
            $validator=Validator::make($data,["week"=>["required",'integer',"between:1,52"],"year" => ['required', 'regex:/^2[0-9]{3}$/']]);
    
        if($validator->fails())
        {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ],422);
        }
        else{
            $validated=$validator->validated();
         
                $weeklyAlarms=$this->getWeeklyAlarms($validated['week'],$validated['year']);
                if($weeklyAlarms['error'])
                {
                    return response()->json([
                        "errors"=>$weeklyAlarms['errors']
    
                    ],404);
    
  
                }
                else
                {
                    return response()->json([

                        "Alarms"=>$weeklyAlarms['statestics'],
    
                    ],200);
    

                  
                }
                 
            
        }
       
       
       
    }

    private function getWeeklyAlarms($week,$year)
    {
        $powerAlarms=PowerAlarm::where('week',$week)->where('year',$year)->get();
        $genAlrms=GenAlarm::where('week',$week)->where('year',$year)->get();
        $HT=HighTempAlarm::where('week',$week)->where('year',$year)->get();
        $downAlarms=DownAlarm::where('week',$week)->where('year',$year)->get();
        $errors=[];
        if(count($powerAlarms)<=0)
        {
             array_push($errors,"Power Alarms does not exist");
        }
        if(count($genAlrms)<=0)
        {
             array_push($errors,"Gen Alarms does not exist");
        }
        if(count($HT)<=0)
        {
             array_push($errors,"High Temp Alarms does not exist");
        }
        if(count( $downAlarms)<=0)
        {
            array_push($errors,"Down Alarms does not exist");

        }
        if(count($errors)>0)
        {
            $notFound["error"]=true;
            $notFound["errors"]=$errors;
            return $notFound;
        }
        else
        {
            $statestics=new WeeklyStatestics($powerAlarms,$genAlrms,$HT,$downAlarms);
             $zonesPowerAlarmsCount=$statestics->zonesPowerAlarmsCount();
             $zonesSitesReportedPowerAlarms=$statestics->zonesSitesReportedPowerAlarms();
         
              $zonesDownAlarmsCount=$statestics->zonesDownAlarmsCount();
        


             $zonesHTAlarmsCount=$statestics->zonesHTAlarmsCount();
             $zonesSitesReportedHTAlarms=$statestics->zonesSitesReportedHTAlarms();
             $zonesSitesNumberSitesReportedHTAlarms=$statestics->zonesNumberSitesReporteHTdAlarms();
          

            $zonesGenAlarmsCount=$statestics->zonesGenAlarmsCount();
            $zonesSitesReportedGenAlarms=$statestics->zonesSitesReportedGenAlarms();
            $zonesSitesNumberSitesReportedGenAlarms=$statestics->zonesNumberSitesReporteGendAlarms();
           
            $data["period"]="week";
            $data["period_No"]=$week;
             $data['zonesPowerAlarmsCount']=$zonesPowerAlarmsCount;
             $data['zonesSitesReportedPowerAlarms']=$zonesSitesReportedPowerAlarms;
          
             $data['zonesDownAlarmsCount']=$zonesDownAlarmsCount;
         

             $data["zonesHTAlarmsCount"]=$zonesHTAlarmsCount; 
             $data['zonesSitesReportedHTAlarms']=$zonesSitesReportedHTAlarms;
             $data['zonesNumberSitesReportedHTAlarms']=$zonesSitesNumberSitesReportedHTAlarms;

            $data["zonesGenAlarmsCount"]=$zonesGenAlarmsCount;
            $data['zonesSitesReportedGenAlarms']= $zonesSitesReportedGenAlarms;
            $data['zonesNumberSitesReportedGenAlarms']=$zonesSitesNumberSitesReportedGenAlarms;

          

            $notFound['error']=false;
            $notFound['statestics']=$data;

            return $notFound;

        }

    }
     
   
}
