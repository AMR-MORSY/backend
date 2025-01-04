<?php

namespace App\Services\NUR;

use App\Models\Modifications\Modification;

class Durations{

    public static function DurationMin(string $start_time, string $end_time):int
    {
        $start = strtotime($start_time);
        $end = strtotime($end_time);
        $minutes =intdiv(($end - $start) , 60) ;


        return $minutes ;

    }

  

    public static function DurationHr(int $minutes):string
    {
        $newMin = $minutes % 60;
        $newHrs = floor($minutes / 60);
        $durationHr = $newHrs . ":" . $newMin;

        return $durationHr;

    }

    public static function transformDate($value, $format = 'Y-m-d H:i')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {

            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }

    public static function getMonth(string $date):int
    {
        return (int) substr($date, 5, 2);
    }
    public static function calculate_month_days(string $date):int
    {
       
        switch ($date) {
            case '01':
               $days = 31;
                break;
            case '02':
               $days = 28;
                break;
            case '03':
               $days = 31;
                break;
            case '04':
               $days = 30;
                break;
            case '05':
               $days = 31;
                break;
            case '06':
               $days = 30;
                break;
            case '07':
               $days = 31;
                break;
            case '08':
               $days = 31;
                break;
            case '09':
               $days = 30;
                break;
            case '10':
               $days = 31;
                break;
            case '11':
               $days = 30;
                break;
            case '12':
               $days = 31;
                break;
        }
        return$days;
    }

    

}