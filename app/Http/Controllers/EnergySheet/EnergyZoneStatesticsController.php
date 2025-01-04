<?php

namespace App\Http\Controllers\EnergySheet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EnergySheet\DownAlarm;
use App\Models\EnergySheet\HighTempAlarm;
use Illuminate\Support\Facades\Validator;
use App\Exports\Energy\ZonesHTAlarmsExport;
use App\Models\EnergySheet\PowerAlarm;
use App\Services\EnergyAlarms\DownAlarmsHelpers;

class EnergyZoneStatesticsController extends Controller
{
    private function convertMinutesToHours($minutes)
    {
        $quotient = (int)($minutes / 60);
        $remainder = $minutes % 60;
        return " $quotient:$remainder";
    }
    public function downloadZoneHTAlarms(Request $request)
    {
        $ruls = [
            "period_No" => ["required", "regex:/^(?:[1-9]|[1-4][0-9]|5[0-2])$/"],
            "zone" => ["required", "regex:/^Cairo South|Cairo East|Cairo North|Giza$/"],
            "period" => ["required", "regex:/^week|month$/"],

        ];
        $validator = Validator::make($request->all(), $ruls);

        if ($validator->fails()) {
            return response()->json(
                [
                    "errors" => $validator->getMessageBag()->toArray(),

                ],
                422
            );
            $this->throwValidationException(


                $validator

            );
        } else {
            $validated = $validator->validated();
            $sites = [];
            $siteCodes = HighTempAlarm::where("operational_zone", $validated["zone"])->where($validated["period"], $validated["period_No"])->get();
            $siteCodes = $siteCodes->groupBy("site_code");

            foreach ($siteCodes as $key => $codes) {
                $subs = [];


                $siteCode = $codes->sortByDesc("duration");
                $siteCode = $siteCode->first();
                array_push($subs, $siteCode["site_name"]);
                array_push($subs, $siteCode["site_code"]);
                array_push($subs, $codes->count());
                array_push($subs, $this->convertMinutesToHours($siteCode["duration"]));
                // $subs["siteName"] =  $siteCode->site_name;
                // $subs["siteCode"] =  $siteCode->site_code;
                // $subs["count"]=$codes->count();
                // $subs["highest_duration"] = $this->convertMinutesToHours($siteCode->duration);

                array_push($sites, $subs);
            }

            return new ZonesHTAlarmsExport($sites);
            // return response()->json([
            //     "site"=>$sites,

            // ]


            // ) ;



        }
    }

    private function zoneWeekYearValidation($zone, $week, $year)
    {
        $data = [
            "zone" => $zone,
            "week" => $week,
            "year" => $year

        ];
        $validator = Validator::make($data, ["week" => ["required", 'integer', "between:1,52"], "year" => ['required', 'regex:/^2[0-9]{3}$/'], "zone" => ['required', 'regex:/^Cairo South|Cairo East|Cairo North|Giza$/']]);
       return $validator;

    }
    public function zonesSitesReportedDownAlarms($zone, $week, $year)
    {
        $this->authorize("viewAny",DownAlarm::class);
        $validator =$this->zoneWeekYearValidation($zone,$week,$year);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        } else {
            $validated = $validator->validated();

            $downAlarms = DownAlarm::where('week', $validated["week"])->where('year', $validated['year'])->where("operational_zone", $validated['zone'])->get();

            if (count($downAlarms) <= 0) {
                return response()->json([
                    "errors" => "Down Alarms does not exist"
                ], 422);
            } else {
                $statestics = new DownAlarmsHelpers($downAlarms);
                $sites = $statestics->zoneSitesReportedDownAlarms();

                return response()->json([
                    "sites" => $sites

                ], 200);
            }
        }
    }
    public function zoneSitesDownWithoutPowerAlarms($zone, $week, $year)
    {
        $this->authorize("viewAny",DownAlarm::class);
        $validator =$this->zoneWeekYearValidation($zone,$week,$year);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        } else {
            $validated = $validator->validated();

            $downAlarms = DownAlarm::where('week', $validated["week"])->where('year', $validated['year'])->where("operational_zone", $validated['zone'])->get();
            $powerAlarms=PowerAlarm::where('week', $validated["week"])->where('year', $validated['year'])->where("operational_zone", $validated['zone'])->get();
            $statestics = new DownAlarmsHelpers($downAlarms);

            $sites = $statestics->zoneSitesDownWithoutPowerAlarms($powerAlarms);

            return response()->json([
                "sites"=>$sites

            ],200);
        }


    }
    public function zoneDownSitesAfterPowerAlarm($zone, $week, $year)
    {
        $this->authorize("viewAny",DownAlarm::class);
        $validator =$this->zoneWeekYearValidation($zone,$week,$year);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        } else {
            $validated = $validator->validated();

            $downAlarms = DownAlarm::where('week', $validated["week"])->where('year', $validated['year'])->where("operational_zone", $validated['zone'])->get();
            $powerAlarms=PowerAlarm::where('week', $validated["week"])->where('year', $validated['year'])->where("operational_zone", $validated['zone'])->get();
            $statestics = new DownAlarmsHelpers($downAlarms);

            $sites = $statestics->zoneDownSitesAfterPowerAlarm($powerAlarms);

            return response()->json([
                "sites"=>$sites

            ],200);
        }

    }
}
