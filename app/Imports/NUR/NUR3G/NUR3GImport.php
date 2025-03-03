<?php

namespace App\Imports\NUR\NUR3G;

use App\Models\NUR\NUR3G;
use App\Services\NUR\Durations;


use App\Services\NUR\WeeklyNUR;
use App\Services\NUR\MonthlyNUR;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;


//The trim() function in PHP is used to remove whitespace or other specified characters from the beginning and end of a string. 

HeadingRowFormatter::extend('custom', function($value, $key) {
    
    
    return strtolower(trim(str_replace(".","",$value))); 
    
    // And you can use heading column index.
    // return 'column-' . $key; 
});


class NUR3GImport implements ToModel, WithHeadingRow,WithValidation
{
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


    public function rules(): array
    {
       
        return  NUR3GImportService::rules();
    }
    public function model(array $row)
    {
       
        return new NUR3G(NUR3GImportService::prepareModel($row,$this->week,$this->year,$this->technology_cells,$this->total_net_cells));
    }
    public function prepareForValidation(array $row)
    {
        return NUR3GImportService::prepareValidation($row);
        
    
            
      
       
    }
   
   
  
   
}
