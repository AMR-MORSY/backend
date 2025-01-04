<?php

namespace App\Http\Controllers\EnergySheet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EnergySheet\GenAlarm;
use App\Models\EnergySheet\PowerAlarm;
use App\Models\EnergySheet\HighTempAlarm;
use Illuminate\Support\Facades\Validator;
use App\Exports\Energy\SiteGenAlarmsExport;
use App\Exports\Energy\SitePowerAlarmsExport;
use App\Exports\Energy\SiteHighTempAlarmsExport;
use App\Models\EnergySheet\DownAlarm;
use App\Services\EnergyAlarms\DownAlarmsHelpers;

class EnergySiteStatesticsController extends Controller
{
    public function sitePowerAlarms(Request $request)
    {
        $this->authorize("viewAny",PowerAlarm::class);
        $ruls = [
            "site_code" => "required|exists:sites,site_code",


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

            $alarms=PowerAlarm::where("site_code",$validated['site_code'])->get();
            return response()->json([

                "alarms"=>$alarms,

            ],200);
        }

    }

    public function siteHighTempAlarms(Request $request)
    {
        $this->authorize("viewAny",HighTempAlarm::class);
        $ruls = [
            "site_code" => "required|exists:sites,site_code",


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

            $alarms=HighTempAlarm::where("site_code",$validated['site_code'])->get();
            return response()->json([

                "alarms"=>$alarms,

            ],200);
        }

    }

    public function siteGenAlarms(Request $request)
    {
        $this->authorize("viewAny",GenAlarm::class);
        $ruls = [
            "site_code" => "required|exists:sites,site_code",


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

            $alarms=GenAlarm::where("site_code",$validated['site_code'])->get();
            return response()->json([

                "alarms"=>$alarms,

            ],200);
        }

    }

    public function siteBatteriesHealth(Request $request)
    {
        $this->authorize("viewAny",DownAlarm::class);
        $ruls = [
            "site_code" => "required|exists:sites,site_code",


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
            $siteDownAlarms=DownAlarm::where("site_code",$validated["site_code"])->get();
            $sitePowerAlarms=PowerAlarm::where("site_code",$validated["site_code"])->get();

            $statestics=new DownAlarmsHelpers($siteDownAlarms);
            $statestics=$statestics->siteBatteriesHealth($sitePowerAlarms);
            return response()->json([
                "statestics"=>$statestics

            ],200);

        }

    }

    public function siteDownAlarmsGroupedByWeek(Request $request)
    {
        $this->authorize("viewAny",DownAlarm::class);
        
        $ruls = [
            "site_code" => "required|exists:sites,site_code",


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
            $siteDownAlarms=DownAlarm::where("site_code",$validated["site_code"])->get();
            $statestics=new DownAlarmsHelpers($siteDownAlarms);
            $statestics=$statestics->siteDownAlarmsGroupedByWeek();
            return response()->json([
                "statestics"=>$statestics

            ],200);
        }

    }

    public function downloadSitePowerAlarms(Request $request)
    { $this->authorize("viewAny",DownAlarm::class);
        $ruls = [
            "siteCode" => "required|exists:sites,site_code",


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

            return new SitePowerAlarmsExport($validated['siteCode']);
          
        }

    }

    public function downloadSiteHighTempAlarms(Request $request)
    {
        $this->authorize("viewAny",DownAlarm::class);
        $ruls = [
            "siteCode" => "required|exists:sites,site_code",


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

            return new SiteHighTempAlarmsExport($validated['siteCode']);
          
        }

    }
    public function downloadSiteGenAlarms(Request $request)
    {
        $this->authorize("viewAny",DownAlarm::class);
        $ruls = [
            "siteCode" => "required|exists:sites,site_code",


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

            return new SiteGenAlarmsExport($validated['siteCode']);
          
        }

    }
}
