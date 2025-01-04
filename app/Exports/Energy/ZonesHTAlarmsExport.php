<?php

namespace App\Exports\Energy;

use Maatwebsite\Excel\Excel;

use App\Models\EnergySheet\HighTempAlarm;
use Maatwebsite\Excel\Concerns\FromArray;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;

class ZonesHTAlarmsExport implements FromArray,WithHeadings,Responsable
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Array
    */
    // protected $period,$zone;
      
    // public function __construct($period,$zone)
    // {
    //     $this->period=$period;
    //     $this->zone=$zone;
        
    // }
    protected $sites;
    public function __construct($sites)
    {
        $this->sites=$sites;
        
    }
    private $writerType = Excel::XLSX;

    private $fileName = 'zoneHighTempAlarms.xlsx';
    
    /**
    * Optional headers
    */
    private $headers = [
        'Content-Type' => 'text/xlsx',
    ];
   
    public function array():array
    {
        // return HighTempAlarm::where("week",$this->period)->where("operational_zone",$this->zone)->get();
       
      
        

        return $this->sites ;

          
    }
    public function headings(): array
    {
        return [
            "Site Name",
            "Site Code",
            "Count",
            "Highest Duration"

        ];
        // return [
        //     '#',
        //     "Zone",
        //     "operational Zone",
        //     "Area",
        //     "BSC",
        //     "Site Name",
        //     "Site Code ",
        //     "Alarm Name",
        //     "start Date",
        //     "Start Time",
        //     "End Date",
        //     "End Time",
        //     "Duration",
        //     "Week",
        //     "Month",
        //     "Year"  
            
        // ];
    }
}
