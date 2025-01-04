<?php

namespace App\Http\Controllers\Transmission;

use App\Models\Sites\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transmission\WAN;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\countOf;

class WANController extends Controller
{
    // public function getSiteWANS(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         "site_code" => "required|exists:sites,site_code",

    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             "errors" => $validator->getMessageBag()
    //         ], 422);
    //     } else {
    //         $validated = $validator->validated();
    //         $site = Site::where("site_code", $validated["site_code"])->first();
    //         $WANS = $site->wans;
    //         if (count($WANS) > 0) {
    //             return response()->json([
    //                 "message" => "data found",
    //                 "WANS" => $WANS

    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 "message" => "data not found",


    //             ], 200);
    //         }
    //     }
    // }
    public function updateSiteWAN(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "site_code" => "required|exists:sites,site_code",
            "reporting_date" => "required|date",
            "clearance_date" => "nullable|date|after_or_equal:reporting_date",
            "network_element" => ["required", "max:100", "regex:/^[a-zA-Z0-9_+-@%&# \/]+$/"],
            "far_end" => ["required", "max:100", "regex:/^[a-zA-Z0-9_+-@%&# \/]+$/"],
            "office" => ["required", "regex:/^Maadi|Shrouk|New Cairo|Haram|Gisr El Suez|Shoubra|Mohandseen|October|Helwan|New Capital|Nasr City$/"],
            "status" => ["required", "regex:/^Solved|Pending$/"],
            "ATST_feedback" => ["nullable", "max:200", "regex:/^[a-zA-Z0-9_+-@%&# \/]+$/"],
            "maintenance_feedback" => ["nullable", "max:200", "regex:/^[a-zA-Z0-9_+-@%& \/]+$/"],



        ]);
        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->getMessageBag()
            ], 422);
        } else {
            $validated = $validator->validated();
            $WAN = WAN::where("site_code", $validated["site_code"])->first();
            $WAN = $WAN->update($validated);
            $WAN = WAN::where("site_code", $validated["site_code"])->first();
            return response($WAN, 200);
        }
    }
    public function storeSiteWAN(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "site_code" => "required|exists:sites,site_code",
            "reporting_date" => "required|date",
            "clearance_date" => "nullable|date|after_or_equal:reporting_date",
            "network_element" => ["required", "max:100", "regex:/^[a-zA-Z0-9_+-@%&# \/]+$/"],
            "far_end" => ["required", "max:100", "regex:/^[a-zA-Z0-9_+-@%&# \/]+$/"],
            "office" => ["required", "regex:/^Maadi|Shrouk|New Cairo|Haram|Gisr El Suez|Shoubra|Mohandseen|October|Helwan|New Capital|Nasr City$/"],
            "status" => ["required", "regex:/^Solved|Pending$/"],
            "ATST_feedback" => ["nullable", "max:200", "regex:/^[a-zA-Z0-9_+-@%&# \/]+$/"],
            "maintenance_feedback" => ["nullable", "max:200", "regex:/^[a-zA-Z0-9_+-@%&# \/]+$/"],



        ]);
        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->getMessageBag()
            ], 422);
        } else {
            $validated = $validator->validated();
            $WAN = WAN::create($validated);


            return response($WAN, 200);
        }
    }
}
