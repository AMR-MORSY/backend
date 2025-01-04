<?php

namespace App\Exports\sites;

use App\Models\Cascade;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;

class AllCascadesExport implements FromCollection,Responsable,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    // protected $cascades;
    // public function __construct($cascades )
    // {
    //     $this->cascades=$cascades;
        
    // }
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection

    */
    private $fileName = 'AllCascades.xlsx';
    
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
       
        return Cascade::all() ;
    }
    public function headings(): array
    {
        return [
            '#',
            'nodal_id',
            'nodal_code',
            "nodal_name",
            "cascade_id",
            "cascade_code",
            "cascade_name",
              
        ];
    }
 
}
