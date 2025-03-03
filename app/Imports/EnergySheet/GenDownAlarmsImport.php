<?php

namespace App\Imports\EnergySheet;

use App\Services\NUR\Durations;
use App\Models\EnergySheet\GenAlarm;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Events\AfterImport;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Imports\EnergySheet\AlarmsImportService;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

HeadingRowFormatter::extend('custom', function($value, $key) {
    
    
    return strtolower(trim(str_replace(".","",$value))); 
    
    // And you can use heading column index.
    // return 'column-' . $key; 
});

HeadingRowFormatter::default('custom');

class GenDownAlarmsImport implements ToModel ,WithHeadingRow ,WithBatchInserts ,WithChunkReading,WithValidation
{
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public $week, $year;

    public function headingRow(): int
    {
        return 1; // Specify the row number of the heading row
    }

    public function __construct($week, $year)
    {
        $this->week = $week;
        $this->year = $year;
    }
  
    // public function transformDate($value, $format = 'Y-m-d')
    // {
    //     try {
    //         return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
    //     } catch (\ErrorException $e) {
    //         return \Carbon\Carbon::createFromFormat($format, $value);
    //     }
    // }
    // public function calculate_duration($value)
    // {
    //     if (intval($value) > 0) {
    //         $inval_min = intval($value) * 24 * 60;
    //         $decimal_min = $value - intval($value);
    //         $decimal_min = Date::excelToTimestamp($decimal_min);
    //         $decimal_min = intval($decimal_min / 60);
    //         $total_min = $decimal_min + $inval_min;
    //         $min = $total_min;
    //     } else {
    //         $decimal_min = Date::excelToTimestamp($value);
    //         $decimal_min = intval($decimal_min / 60);
    //         $min = $decimal_min;
    //     }
    //     return $min;
    // }
    // public function transformTime($value)
    // {
    //     $time = Date::excelToTimestamp($value);
    //     return $time = date("H:i:s", $time);
      
    // }

    public function rules(): array
    {
        return AlarmsImportService::rule("generator");
        // return [
        //     "*.Site Code" => ["required",  "regex:/^([0-9a-zA-Z]{4,6}(up|UP))|([0-9a-zA-Z]{4,6}(ca|CA))|([0-9a-zA-Z]{4,6}(de|DE))$/"],
        //     "*.Site Name" => ["required", "regex:/^([0-9a-zA-Z_-]|\s){3,60}$/"],
        //     "*.BSC Name" => ["required", "string"],
        //     "*.Area" => ["required", "regex:/^[0-9a-zA-Z_-]{3,50}$/"],
        //     "*.Alarm Name" => ["required", "string"],
        //     "*.Occurred On(Date)" => ["required", 'date'],
        //     "*.Cleared On(Date)" => ["required","date"],
        //     "*.Occurred On(Time)" => ["required","date_format:H:i:s"],
        //     "*.Cleared On(Time)" => ["required","date_format:H:i:s"],
        //     "*.Duration" => ["required","integer"],
        //     "*.OZ" => ["required", "regex:/^Cairo South|Cairo East|Cairo North|Giza$/"],
        //     "*.Zone" => ["required", "regex:/^(Cairo)$/"],

        //     "*.Config"=>["required","regex:/^(DG)|(DG - Hybrid (H))|(EC\+DG (3 Month))|(EC\+SCG)|(EC\+DG)|(EC\+SCG (3 Month))$/"],

        // ];
    }
    public function model(array $row)
    {
        $modelArray=AlarmsImportService::model($row, $this->week, $this->year);
        $modelArray['configuration']=$row['config'];///////////////adding the field configuration
        return new GenAlarm($modelArray);
        // return new GenAlarm([

        //     "zone" => $row['Zone'],
        //     'operational_zone' => $row['OZ'],
        //     "area" => $row['Area'],
        //     'bsc' => $row['BSC Name'],
        //     'site_name' => $row['Site Name'],
        //     'site_code' => $row['Site Code'],
        //     'alarm_name' => $row['Alarm Name'],
        //     // "duration" => $this->calculate_duration($row["Duration"]),
        //     "duration"=>$row["Duration"],

        //     // 'start_date' => $this->transformDate($row['Occurred On(Date)']),
        //     "start_date" => $row['Occurred On(Date)'],
        //     // "start_time" => $this->transformTime($row['Occurred On(Time)']),
        //     "start_time" =>$row['Occurred On(Time)'],

        //     // 'end_date' => $this->transformDate($row['Cleared On(Date)']),
        //     "end_date" => $row['Cleared On(Date)'],
        //     // "end_time" => $this->transformTime($row['Cleared On(Time)']),
        //     "end_time" =>$row['Cleared On(Time)'],

        //     "week" => $this->week,
        //     "month"=>Durations::getMonth($row['Occurred On(Date)']),
        //     'year' => $this->year,
        //     "configuration"=>$row["Config"],
        // ]);
    }
    public function prepareForValidation(array $row)
    {
        return AlarmsImportService::prepareForValidation($row);
        // if(!is_int($row["Occurred On(Date)"]))
        // {
        //     $row["Occurred On(Date)"]=null;
           
        //     return $row; 
        // }
        // if(!is_float($row["Occurred On(Time)"]))
        // {
        //     $row["Occurred On(Time)"]=null;
        //     return $row; 

        // }
        // if(!is_int($row["Cleared On(Date)"]))
        // {
        //     $row["Cleared On(Date)"]=null;
         
        //     return $row; 

        // }
        // if(!is_float($row["Cleared On(Time)"]))
        // {
        //     $row["Cleared On(Time)"]=null;
        //     return $row; 

        // }
        // if(!is_float($row["Duration"]))
        // {
        //     $row["Duration"]=null;
        //     return $row; 

        // }
     
     
       
        // else
        // {
        //     $row["Occurred On(Date)"] = \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row["Occurred On(Date)"]));
        //     $start_time = Date::excelToTimestamp( $row["Occurred On(Time)"]);
        //     $row["Occurred On(Time)"]= date("H:i:s", $start_time);
        //     $row["Cleared On(Date)"] = \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row["Cleared On(Date)"]));
        //     $cleared_time = Date::excelToTimestamp( $row["Cleared On(Time)"]);
        //     $row["Cleared On(Time)"]= date("H:i:s", $cleared_time);
        //     $row["Duration"]=$this->calculate_duration( $row["Duration"]);

        //     return $row;

        // }
       
    }
   

    public function batchSize(): int
    {
        return 100;
    }
    public function chunkSize(): int
    {
        return 100;
    }
}
