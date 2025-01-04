<?php

namespace App\Http\Controllers\Transmission;

use App\Models\Sites\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transmission\IP_traffic;
use App\Models\Transmission\WAN;
use App\Models\Transmission\XPIC;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToArray;

class All_TX_ActionsController extends Controller
{

    private function checkSingleTxIssue($issue,$issue_name,$otherIssue1,$otherIssue2)
    {
        $issues = [];
        $message=[];
       
        if(count($issue) > 0) {
            $message[$issue_name] = "data found";
            $message[$otherIssue1]= "data not found";
            $message[$otherIssue2]= "data not found";

            $issues[$issue_name] = $issue;
        } else {

            $message[$issue_name] = "data not found";
            $message[$otherIssue1]= "data not found";
            $message[$otherIssue2]= "data not found";
            $issues[$issue_name] = [];
        }
        return ["message"=>$message,"issue"=>$issues];
    }
    private function checkingIssues($ip_traffics, $WANS, $XPICS)
    {
        if (count($ip_traffics) > 0) {
            $message["ip_traffics"] = "data found";

            $issue["ip_traffics"] = $ip_traffics;
        } else {

            $message["ip_traffics"] = "data not found";
            $issue["ip_traffics"] = [];
        }
        if (count($WANS) > 0) {
            $message["WANS"] = "data found";

            $issue["WANS"] = $WANS;
        } else {

            $message["WANS"] = "data not found";
            $issue["WANS"] = [];
        }
        if (count($XPICS) > 0) {
            $message["XPICS"] = "data found";

            $issue["XPICS"] = $XPICS;
        } else {

            $message["XPICS"] = "data not found";
            $issue["XPICS"] = [];
        }
        return ["message" => $message, "issue" => $issue];
    }
    public function getSiteTXIssues($site_code)
    {
        $this->authorize("viewAny",WAN::class);
        $data=["site_code"=>$site_code];

        

        $validator = Validator::make($data, [
            "site_code" => "required|exists:sites,site_code",

        ]);
        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->getMessageBag()
            ], 422);
        } else {
            $validated = $validator->validated();
            $site = Site::where("site_code", $validated["site_code"])->first();
            $ip_traffics = $site->ip_traffics;
            $WANS = $site->wans;
            $XPICS = $site->xpics;
            $message = $this->checkingIssues($ip_traffics, $WANS, $XPICS)["message"];
            $issue = $this->checkingIssues($ip_traffics, $WANS, $XPICS)["issue"];

            return response()->json(["messages" => $message, "issues" => $issue], 200);
        }
    }
    public function searchTxIssues($fromDate, $toDate, $issue)
    {
        $this->authorize("viewAny",WAN::class);
        $data = [
            "fromDate" => $fromDate,
            "toDate" => $toDate,
            "issue" => $issue
        ];
        $validator = Validator::make($data, [
            "toDate" => "required|date|after_or_equal:fromDate",
            "fromDate" => "required|date",
            "issue" => ["required", "regex:/^All|WAN|XPIC|iP_traffic$/"]

        ]);
        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->getMessageBag()
            ], 422);
        } else {
            $validated = $validator->validated();
            if ($validated["issue"] == "All") {
                $WANS = WAN::whereDate("reporting_date", ">=", $validated["fromDate"])->whereDate("reporting_date", "<=", $validated["toDate"])->get();
                $XPICS = XPIC::whereDate("reporting_date", ">=", $validated["fromDate"])->whereDate("reporting_date", "<=", $validated["toDate"])->get();
                $ip_traffics = IP_traffic::whereDate("reporting_date", ">=", $validated["fromDate"])->whereDate("reporting_date", "<=", $validated["toDate"])->get();
                $message = $this->checkingIssues($ip_traffics, $WANS, $XPICS)["message"];
                $issue = $this->checkingIssues($ip_traffics, $WANS, $XPICS)["issue"];
                return response()->json(["messages" => $message, "issues" => $issue], 200);
            } elseif ($validated["issue"] == "WAN") {
                $WANS = WAN::whereDate("reporting_date", ">=", $validated["fromDate"])->whereDate("reporting_date", "<=", $validated["toDate"])->get();
                $issue = $this->checkSingleTxIssue($WANS,"WANS","XPICS","ip_traffics")["issue"];
                $message = $this->checkSingleTxIssue($WANS,"WANS","XPICS","ip_traffics")["message"];
              
                return response()->json(["messages" => $message, "issues" => $issue], 200);
            }
            elseif($validated["issue"] == "XPIC")
            {
                $XPICS =XPIC::whereDate("reporting_date", ">=", $validated["fromDate"])->whereDate("reporting_date", "<=", $validated["toDate"])->get();
                $issue = $this->checkSingleTxIssue($XPICS,"XPICS","WANS","ip_traffics")["issue"];
                $message = $this->checkSingleTxIssue($XPICS,"XPICS","XPICS","ip_traffics")["message"];
              
                return response()->json(["messages" => $message, "issues" => $issue], 200);

            }
            elseif($validated["issue"] == "iP_traffic")
            {
                $ip_traffics =IP_traffic::whereDate("reporting_date", ">=", $validated["fromDate"])->whereDate("reporting_date", "<=", $validated["toDate"])->get();
                $issue = $this->checkSingleTxIssue($ip_traffics,"ip_traffics","XPICS","ip_traffics")["issue"];
                $message = $this->checkSingleTxIssue($ip_traffics,"ip_traffics","XPICS","ip_traffics")["message"];
              
                return response()->json(["messages" => $message, "issues" => $issue], 200);

            }
        }
    }
}
