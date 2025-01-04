<?php

namespace App\Exports\NUR;

use App\Models\NUR\NUR4G;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;

class NUR4GExport implements FromCollection,WithHeadings,Responsable
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $site_code;

    private $fileName = 'NUR2G.xlsx';

    public function __construct($site_code)
    {
        $this->site_code=$site_code;
        
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
       

        return NUR4G::where('problem_site_code',$this->site_code)->get();
       
    }
    public function headings(): array
    {
        return [
            '#',
            "impacted Sites",
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
            "Duration_HR",
            "Duration_min",
            "week",
            "gen owner",
            "year",
            'problem Site Code',
            'problem Site Name',
            "month",
            "network cells",
            "monthly NUR",
           
            
        ];
    }
}
