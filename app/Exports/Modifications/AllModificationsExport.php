<?php

namespace App\Exports\Modifications;

use App\Models\Modifications\Modification;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;


class AllModificationsExport implements FromCollection,WithHeadings,Responsable
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $column_name,$column_value;

    private $fileName = 'AllModifications.xlsx';

    public function __construct($column_name,$column_value)
    {
        $this->column_name=$column_name;
        $this->column_value=$column_value;
        
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
      
        return Modification::where($this->column_name,$this->column_value)->get();
      
    }
    public function headings(): array
    {
        return [
            '#',
            "Site Code ",
            "Site Name",
            "Subcontractor",
            "Requester",
            "Action",
            "Status",
            "Project",
            "Request Date",
            "Finish Date",
            "Materials",
            "Cost"  
            
        ];
    }
}
