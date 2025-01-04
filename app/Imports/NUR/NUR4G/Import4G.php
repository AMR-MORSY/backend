<?php

namespace App\Imports\NUR\NUR4G;

use Illuminate\Support\Collection;
use App\Imports\NUR\NUR4G\FM4GImport;
use App\Imports\NUR\NUR4G\NUR4GImport;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Import4G implements WithMultipleSheets 
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
            '4G Network' => new NUR4GImport($this->week, $this->year, $this->technology_cells,$this->total_net_cells),
            '4G F.M' => new FM4GImport($this->week, $this->year, $this->technology_cells,$this->total_net_cells),
        ];
    }
}
