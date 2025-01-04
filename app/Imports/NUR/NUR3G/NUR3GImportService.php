<?php

namespace App\Imports\NUR\NUR3G;

use App\Services\NUR\Durations;
use App\Services\NUR\WeeklyNUR;
use App\Services\NUR\MonthlyNUR;


class NUR3GImportService
{

    public static function rules()
    {
        

        return [

            "problem source site code" => ["required_if:to workgroup,HLGAM", "nullable", 'string'],
            "site name (node b)" => ["required", 'string'],
            "problem source site name" => ["required_if:to workgroup,HLGAM", "nullable", 'string'],
            "rnc" => ["nullable", "regex:/^([0-9a-zA-Z_-]|\s){3,50}$/"],
            "no of cells" => ["required", "regex:/^([1-9][0-9]{0,2}|1000)$/"],
            'system' => ['required', 'string'],
            "sub system" => ['required', 'string'],
            "type" => ['required', "regex:/^Involuntary|Voluntary$/"],
            'solution' => ["required_if:to workgroup,HLGAM", "nullable", 'string'],
            "access problem" => ['required', 'boolean'],
            'force majeure' => ['nullable', 'boolean'],
            'force majeure type' => ['nullable', 'string'],
            "incident start time" => ["required", 'date'],
            "incident end time" => ["required", "date"],
            "operation zone" => ["required", 'string'],
            "generator owner" => ["nullable", "regex:/^Shared|Orange|Rented$/"],
            "action ogs responsible" => ["nullable", "string"],
            "to workgroup" => ['required', "regex:/^ALGAM|HLGAM|NLGAM$/"],
            "ntt office"=>["required_if:to workgroup,HLGAM", "nullable", 'string']


        ];
    }


    public static function prepareValidation($row)
    {
       
        $row["incident start time"] = Durations::transformDate($row["incident start time"]);
          
       
        $row["incident end time"] = Durations::transformDate($row["incident end time"]);
     

       return $row;
    }

    public static function prepareModel($row, $week, $year, $technology_cells, $total_net_cells):array
    {


        $duration_min = Durations::DurationMin($row['incident start time'], $row['incident end time']);
        $duration_hr = Durations::DurationHr($duration_min);
        $weekly_nur = WeeklyNUR::calculate_NUR($duration_min, $row['no of cells'], $technology_cells);
        $combinedNUR=WeeklyNUR::calculateCombinedNUR ($weekly_nur,$technology_cells,$total_net_cells);
        $month_as_number = Durations::getMonth($row['incident start time']);
        $days_of_month = Durations::calculate_month_days($month_as_number);
        $monthly_nur = new MonthlyNUR($days_of_month, $duration_min, $row['no of cells'], $technology_cells);
        return [
            "Action_OGS_responsible"=>$row["action ogs responsible"],
            "impacted_sites"=>$row["site name (node b)"],
            "work_group"=>$row["to workgroup"],
            "RNC"=>strtolower( $row["rnc"]),
            "cells"=>$row["no of cells"],
            "oz"=>strtolower($row["operation zone"]) ,
            "begin"=>$row["incident start time"],
            "end"=>$row["incident end time"],
            "problem_site_code"=>$row["problem source site code"],
            'problem_site_name'=>$row["problem source site name"],
            "week"=>$week,
            "year"=>$year,
            "network_cells_3G"=>$technology_cells,
            "system"=>strtolower($row['system']) ,
            "sub_system"=>strtolower($row["sub system"]) ,
            "nur"=>$weekly_nur,
            'Dur_min'=> $duration_min,
            'Dur_Hr'=>  $duration_hr,
            'type'=>$row['type'],
            "nur_c"=>$combinedNUR,
            'total_network_cells'=>$total_net_cells,
            'solution'=>strtolower($row['solution']) ,
            "gen_owner"=>strtolower($row["generator owner"]) ,
            "access"=>$row['access problem'],
            'Force_Majeure'=>$row['force majeure'],
            'Force_Majeure_type'=>$row['force majeure type'],
            'month'=>$month_as_number,
            'monthly_nur'=> $monthly_nur->calculate_monthly_nur(),
            "office" => $row['ntt office']          

        ];
    }
}
