<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivitiesController extends Controller

{
    private function extractActivities($activities)
    {
        if(count($activities)>0)
        {
            foreach ($activities as $activity) {
                $activity->causer_id = User::find($activity->causer_id)->email;
            };
            return response()->json([
                "success" => true,
                "activities" => $activities
            ],200);
            
        }
        return response()->json([
            "success"=>false,
        ],200);
     

      
    }

    private function viewActivity($id)
    {
        $activitiy = Activity::find($id);
        if ($activitiy) {
            $activitiy->causer_id = User::find($activitiy->causer_id)->email;
            
            return response()->json(["success"=>true,"activity"=>$activitiy],200); 
        }
        return response()->json([
            "success"=>false,
            "message" => "Activity does not exist"
        ], 422);

    }

    public function modificationsActivities()
    {

        $activities = Activity::where("log_name", "modifications")->get();
        return $this->extractActivities($activities);
      
    


      
    }

    public function modificationActivityData($id)
    {
        return $this->viewActivity($id);
       
    }

    public function wanActivities()
    {
        $activities = Activity::where("log_name", "wan")->get();
        return $this->extractActivities($activities);
      
    }
    public function XPICActivities()
    {
        $activities = Activity::where("log_name", "XPIC")->get();
        return $this->extractActivities($activities);
      
    }
    public function IPActivities()
    {
        $activities = Activity::where("log_name", "IPS")->get();
        return $this->extractActivities($activities);
      
    }

    public function transmissionActivityData($id)
    {
        return $this->viewActivity($id);
       

    }

    public function userActivities($id)
    {
        $activities=Activity::where("causer_id",$id)->get();
        return $activities;
    }
}
