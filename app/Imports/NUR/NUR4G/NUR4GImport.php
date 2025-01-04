<?php

namespace App\Imports\NUR\NUR4G;

use App\Models\NUR\NUR4G;
use App\Services\NUR\Durations;
use App\Services\NUR\WeeklyNUR;
use App\Services\NUR\MonthlyNUR;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Imports\NUR\NUR3G\NUR3GImportService;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;


HeadingRowFormatter::extend('custom', function($value, $key) {
    
    
    return strtolower(trim(str_replace(".","",$value))); 
    
    // And you can use heading column index.
    // return 'column-' . $key; 
});

class NUR4GImport implements ToModel,WithHeadingRow, WithValidation
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
        

        $row['rnc']=""; ////Must be added as the prepare Model function is dedicated for importing 3G Model
       
        $modelArray=NUR3GImportService:: prepareModel($row,$this->week,$this->year,$this->technology_cells,$this->total_net_cells);
        $modelArray=array_diff_key($modelArray, array_flip((array) ['RNC']));
        $arr[] = $modelArray;
        
        $arr[0]["network_cells_4G"] = $arr[0]["network_cells_3G"]; //////replacing the key name network_cells_3G with 4G  
        unset($arr[0]["network_cells_3G"]);
        $modelArray=$arr[0];
        
        return new NUR4G($modelArray);
    }
    public function prepareForValidation(array $row)
    {
        return NUR3GImportService::prepareValidation($row);
    
       

       
       
    }
   
   
  
}
