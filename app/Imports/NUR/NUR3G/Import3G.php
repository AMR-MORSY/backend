<?php

namespace App\Imports\NUR\NUR3G;

use App\Imports\NUR\NUR3G\NUR3GImport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Import3G implements WithMultipleSheets 
{
    public $week, $year, $technology_cells,$total_net_cells;



    public function __construct($week, $year, $cells,$total_net_cells)
    {
        $this->week = $week;
        $this->year = $year;
        $this->technology_cells = $cells;
        $this->total_net_cells=$total_net_cells;
    }

    public function sheets(): array
    {
        return [
            '3G Network' => new NUR3GImport($this->week, $this->year, $this->technology_cells,$this->total_net_cells),
            '3G F.M' => new FM3GImport($this->week, $this->year, $this->technology_cells,$this->total_net_cells),
        ];
    }
   
}
