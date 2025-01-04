<?php

namespace App\Imports\NUR\NUR4G;

use App\Models\NUR\FM4G;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Imports\NUR\NUR3G\NUR3GImportService;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class FM4GImport implements ToModel,SkipsEmptyRows,WithHeadingRow, WithValidation
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
        return  NUR3GImportService::rules();
    }

    public function model(array $row)
    {
        // $modelArray=NUR3GImportService:: prepareModel($row,$this->week,$this->year,$this->technology_cells,$this->total_net_cells);
        // $modelArray=array_diff_key($$modelArray, array_flip((array) ['RNC']));
        // $element=[];
        // $element['rnc']="";
        // array_push($row,$element);

        $row['rnc']="";
       
        $modelArray=NUR3GImportService:: prepareModel($row,$this->week,$this->year,$this->technology_cells,$this->total_net_cells);
        $modelArray=array_diff_key($modelArray, array_flip((array) ['RNC']));
        $arr[] = $modelArray;
        
        $arr[0]["network_cells_4G"] = $arr[0]["network_cells_3G"];
        unset($arr[0]["network_cells_3G"]);
        $modelArray=$arr[0];
        return new FM4G($modelArray);
    }
    public function prepareForValidation(array $row)
    {
        return NUR3GImportService::prepareValidation($row);
    }
    
}
