<?php

namespace App\Imports\EnergySheet;

use App\Models\EnergySheet\DownAlarm;
use App\Services\NUR\Durations;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Events\AfterImport;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithValidation;
use SebastianBergmann\Timer\Duration;



class AlarmsImportService {

    public static function rule($alarmName=null)
    {
       
        if(isset($alarmName))
        {
           return [
            "site code" => ["required",  "regex:/^([0-9a-zA-Z]{4,6}(up|UP))|([0-9a-zA-Z]{4,6}(ca|CA))|([0-9a-zA-Z]{4,6}(de|DE))$/"],
            "site name" => ["required", "regex:/^([0-9a-zA-Z_-]|\s){3,60}$/"],
            "alarm name" => ["required", "string"],
            "occurred on date" => ["required", 'date'],
            "cleared on date" => ["required","date"],
            "occurred on time" => ["required","date_format:H:i:s"],
            "cleared on time" => ["required","date_format:H:i:s"],
            "duration" => ["required","integer"],
            "oz" => ["required", "regex:/^Cairo South|Cairo East|Cairo North|Giza$/"],
            "zone" => ["required", "regex:/^(Cairo)$/"],
            "config"=>['required','string']


           ];
        }
        return [
            "site code" => ["required",  "regex:/^([0-9a-zA-Z]{4,6}(up|UP))|([0-9a-zA-Z]{4,6}(ca|CA))|([0-9a-zA-Z]{4,6}(de|DE))$/"],
            "site name" => ["required", "regex:/^([0-9a-zA-Z_-]|\s){3,60}$/"],
            "alarm name" => ["required", "string"],
            "occurred on date" => ["required", 'date'],
            "cleared on date" => ["required","date"],
            "occurred on time" => ["required","date_format:H:i:s"],
            "cleared on time" => ["required","date_format:H:i:s"],
            "duration" => ["required","integer"],
            "oz" => ["required", "regex:/^Cairo South|Cairo East|Cairo North|Giza$/"],
            "zone" => ["required", "regex:/^(Cairo)$/"],

           
        ];

    }

    protected function calculate_duration($value)
    {
        if (intval($value) > 0) {
            $inval_min = intval($value) * 24 * 60;
            $decimal_min = $value - intval($value);
            $decimal_min = Date::excelToTimestamp($decimal_min);
            $decimal_min = intval($decimal_min / 60);
            $total_min = $decimal_min + $inval_min;
            $min = $total_min;
        } else {
            $decimal_min = Date::excelToTimestamp($value);
            $decimal_min = intval($decimal_min / 60);
            $min = $decimal_min;
        }
        return $min;
    }

    public static function prepareForValidation(array $row)
    {
        if(!is_int($row["occurred on date"]))
        {
            $row["occurred on date"]=null;
           
            return $row; 
        }
        if(!is_float($row[ "occurred on time"]))
        {
            $row[ "occurred on time"]=null;
            return $row; 

        }
        if(!is_int($row["cleared on date"]))
        {
            $row["cleared on date"]=null;
         
            return $row; 

        }
        if(!is_float($row["cleared on time"]))
        {
            $row["cleared on time"]=null;
            return $row; 

        }
        if(!is_float($row["duration"]))
        {
            $row["duration"]=null;
            return $row; 

        }
     
     
       
        else
        {
            $row["occurred on date"] = \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row["occurred on date"]));
            $start_time = Date::excelToTimestamp( $row["occurred on time"]);
            $row["occurred on time"]= date("H:i:s", $start_time);
            $row["cleared on date"] = \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row["cleared on date"]));
            $cleared_time = Date::excelToTimestamp( $row["cleared on time"]);
            $row["cleared on time"]= date("H:i:s", $cleared_time);
            $instance = new self();
            $row["duration"]=$instance->calculate_duration( $row["duration"]);

            return $row;

        }
       
    }

    public static function model(array $row,$week,$year)
    {
        return [
            "zone" => $row['zone'],
            'operational_zone' => $row['oz'],
        
            'site_name' => $row['site name'],
            'site_code' => $row['site code'],
            'alarm_name' => $row['alarm name'],
         
            "duration"=>$row["duration"],

          
            "start_date" => $row["occurred on date"],
          
            "start_time" =>$row["occurred on time"],

           
            "end_date" => $row["cleared on date"],
         
            "end_time" =>$row["cleared on time"],
 
            "week" => $week,
         
            'year' => $year

        ];
    }

}