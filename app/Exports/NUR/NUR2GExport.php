<?php

namespace App\Exports\NUR;

use App\Models\NUR\NUR2G;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;

class NUR2GExport implements FromCollection,Responsable,WithHeadings
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
        // $siteNUR=NUR2G::where('problem_site_code',$this->site_code)->get();

        return NUR2G::where('problem_site_code',$this->site_code)->get();
        // return NUR2G::all();
    }
    public function headings(): array
    {
        return [
            '#',
            "impacted Sites",
            "BSC",
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
            "year",
            "network cells",
            "gen owner",
            'problem Site Code',
            'problem Site Name',
            "month",
            "monthly NUR",
           
            
        ];
    }
}
