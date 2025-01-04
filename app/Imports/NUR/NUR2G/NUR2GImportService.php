<?php

namespace App\Imports\NUR\NUR2G;

use App\Services\NUR\Durations;
use App\Services\NUR\WeeklyNUR;
use App\Services\NUR\MonthlyNUR;

class NUR2GImportService{

    public static function rules()
    {
        return [


            "problem source site code" => ["required_if:tt ogs responsible,HLGAM","nullable",'string'],
            "site name" => ["required_if:tt ogs responsible,HLGAM","nullable",'string'],
            "problem source site name" => ["required_if:tt ogs responsible,HLGAM","nullable", "string"],
            "bsc" => ["nullable", "regex:/^([0-9a-zA-Z_-]|\s){3,50}$/"],
            "cells" => ["required", "regex:/^([1-9][0-9]{0,2}|1000)$/"],
            'system' => ['string'],
            "sub system" => ['string'],
            "type" => ['required', "regex:/^Involuntary|Voluntary$/"],
            'solution' => ["required_if:tt ogs responsible,HLGAM","nullable", 'string'],
            "access problem" => ['nullable', 'boolean'],
            'force majeure' => ['nullable', 'boolean'],
            'force majeure type' => ['nullable', 'string'],
            "begin" => ["required", 'date'],
            "end" => ["required", "date"],
            "operation zone" => ["string"],
            "generator owner"=>["nullable","regex:/^Shared|Orange|Rented$/"],
            "action ogs responsible"=>["nullable","string"],
            "tt ogs responsible"=>['required',"regex:/^ALGAM|HLGAM|NLGAM$/"],
            "ntt office"=>['required_if:tt ogs responsible,HLGAM',"nullable", 'string']



        ];
    }


    public static function prepareValidation($row)
    {
        $row["begin"] = Durations::transformDate($row["begin"]);


        $row["end"] = Durations::transformDate($row["end"]);


        return $row;
    }

    public static function prepareModel($row,$week,$year,$technology_cells,$total_net_cells)
    {
       
       
        $duration_min = Durations::DurationMin($row['begin'], $row['end']);
        $duration_hr = Durations::DurationHr($duration_min);
        $weekly_nur = WeeklyNUR::calculate_NUR($duration_min, $row['cells'], $technology_cells);
        $combinedNUR=WeeklyNUR::calculateCombinedNUR ($weekly_nur,$technology_cells,$total_net_cells);
        $month_as_number = Durations::getMonth($row['begin']);
        $days_of_month = Durations::calculate_month_days($month_as_number);
        $monthly_nur = new MonthlyNUR($days_of_month, $duration_min, $row['cells'], $technology_cells);
        return [
            "Action_OGS_responsible"=>$row["action ogs responsible"],
            "work_group"=>$row["tt ogs responsible"],
            "impacted_sites" => $row["site name"],
            "BSC" => strtolower( $row["bsc"]),
            "cells" => $row["cells"],
            "oz" => strtolower($row["operation zone"]) ,
            "begin" => $row["begin"],
            "end" => $row["end"],
            "problem_site_code" => $row["problem source site code"],
            'problem_site_name' => $row["problem source site name"],
            "week" => $week,
            "year" => $year,
            "system" => strtolower($row['system']) ,
            "sub_system" => strtolower($row["sub system"]) ,
            "nur" => $weekly_nur,
            'total_network_cells'=>$total_net_cells,
            'Dur_min' => $duration_min,
            'Dur_Hr' => $duration_hr,
            'type' => $row['type'],
            "nur_c"=>$combinedNUR,
            'solution' => strtolower($row['solution']),
            "access" => $row['access problem'],
            'Force_Majeure' => $row['force majeure'],
            'Force_Majeure_type' => $row['force majeure type'],
            'month' => $month_as_number,
            "network_cells_2G"=>$technology_cells,
            "gen_owner"=> strtolower($row["generator owner"]) ,
            'monthly_nur' => $monthly_nur->calculate_monthly_nur(),
            "office" => $row['ntt office']          


        ];

    }




}
