<?php

namespace App\Imports\NUR\NUR2G;

use App\Imports\NUR\NUR2G\NUR2GImportService;
use App\Models\NUR\NUR2G;
use App\Services\NUR\Durations;
use App\Services\NUR\WeeklyNUR;
use App\Services\NUR\MonthlyNUR;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

// HeadingRowFormatter::default('none');

HeadingRowFormatter::extend('custom', function($value, $key) {
    
    
    return strtolower(trim(str_replace(".","",$value))); 
    
    // And you can use heading column index.
    // return 'column-' . $key; 
});

class NUR2GImport implements ToModel,SkipsEmptyRows,WithHeadingRow, WithValidation
{

    use Importable;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */


     
    public $week, $year, $technology_cells,$total_net_cells;



    public function __construct($week, $year, $cells,$total_net_cells)
    {
        $this->week = $week;
        $this->year = $year;
        $this->technology_cells = $cells;
        $this->total_net_cells=$total_net_cells;
    }



    public function rules():array
    {
      
      return  NUR2GImportService::rules();
    }
    


   
    public function model(array $row)
    {
       

        return new NUR2G(NUR2GImportService::prepareModel($row,$this->week,$this->year,$this->technology_cells,$this->total_net_cells));
    }
  
  
  
    public function prepareForValidation(array $row)
    {
        return NUR2GImportService::prepareValidation($row);
       
    }
  



   
}
