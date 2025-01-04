<?php

namespace App\Exports\NUR;


use App\Models\NUR\NUR3G;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;

class NUR3GExport implements FromCollection,Responsable,WithHeadings
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $site_code;

   

    public function __construct($site_code)
    {
        $this->site_code=$site_code;
      
    }
    private $fileName = "NUR3G.xlsx";
    
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
       

        return NUR3G::where('problem_site_code',$this->site_code)->get();
       
    }
    public function headings(): array
    {
        return [
            '#',
            "impacted Sites",
            "RNC",
            "cells",
            "Begin",
            "End",
            "NUR",
            "system",
            "Subsystem",
            "solution",
            "OZ",
            "type",
            "Access",
            "Force Majeure",
            "Force Majeure type",
            "technology",
            "gen owner",
            "Duration_HR",
            "Duration_min",
            "week",
            "year",
            'problem Site Code',
            'problem Site Name',
            "month",
            "network cells",
            "monthly NUR",
           
            
        ];
    }
}
