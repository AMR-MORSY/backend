<?php

namespace App\Imports\NUR\NUR2G;

use Illuminate\Support\Collection;
use App\Imports\NUR\NUR2G\NUR2GImport;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Import2G implements WithMultipleSheets 
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
            '2G Network' => new NUR2GImport($this->week, $this->year, $this->technology_cells,$this->total_net_cells),
            '2G F.M' => new FM2GImport($this->week, $this->year, $this->technology_cells,$this->total_net_cells),
        ];
    }
}
