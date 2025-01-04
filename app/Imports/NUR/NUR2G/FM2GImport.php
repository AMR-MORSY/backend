<?php

namespace App\Imports\NUR\NUR2G;

use App\Models\NUR\FM2G;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
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


class FM2GImport implements ToModel,SkipsEmptyRows,WithHeadingRow, WithValidation
{
  
   
    
     
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
        return new FM2G(NUR2GImportService::prepareModel($row,$this->week,$this->year,$this->technology_cells,$this->total_net_cells));
    }

    public function prepareForValidation(array $row)
    {
        return NUR2GImportService::prepareValidation($row);
    }
}
