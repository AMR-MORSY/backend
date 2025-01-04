<?php

namespace App\Imports\NUR\NUR3G;


use App\Models\NUR\FM3G;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class FM3GImport implements ToModel,SkipsEmptyRows,WithHeadingRow, WithValidation

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
        return new FM3G(NUR3GImportService::prepareModel($row,$this->week,$this->year,$this->technology_cells,$this->total_net_cells));
    }

    public function prepareForValidation(array $row)
    {
        return NUR3GImportService::prepareValidation($row);
    }
    
}
