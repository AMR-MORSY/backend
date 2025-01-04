<?php

namespace App\Exports\Energy;

use Maatwebsite\Excel\Excel;
use App\Models\EnergySheet\HighTempAlarm;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiteHighTempAlarmsExport implements FromCollection,WithHeadings,Responsable
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $siteCode;

    private $fileName = 'siteHighTempAlarms.xlsx';

    public function __construct($siteCode)
    {
        $this->siteCode=$siteCode;
     
        
    }
    
    /**
    * Optional Writer Type
    */
    private $writerType = Excel::XLSX;
    
    /**
    * Optional headers
    */
    private $headers = [
        'Content-Type' => 'text/xlsx',
    ];
    public function collection()
    {
      
        return HighTempAlarm::where("site_code",$this->siteCode)->get();
      
    }
    public function headings(): array
    {
        return [
            '#',
            "Zone",
            "operational Zone",
            "Area",
            "BSC",
            "Site Name",
            "Site Code ",
            "Alarm Name",
            "start Date",
            "Start Time",
            "End Date",
            "End Time",
            "Duration",
            "Week",
            "Month",
            "Year"  
            
        ];
    }
}
