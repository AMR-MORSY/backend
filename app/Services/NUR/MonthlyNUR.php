<?php

namespace App\Services\NUR;

use App\services\NurHelpers\DurationMin\DurationMin;
use App\services\NurHelpers\GetMonth\GetMonth;
use App\services\NurHelpers\MonthDays\MonthDays;
use Illuminate\Validation\Rules\In;

class MonthlyNUR
{


    private int   $days_of_month,$duration_min, $cells,$total_network_cells;

    

    public function __construct( int $days_of_month, int $duration_min, int $cells, int $total_network_cells)
    {
        $this->days_of_month=$days_of_month;
        $this->cells=$cells;
        $this->duration_min=$duration_min;
        $this->total_network_cells=$total_network_cells;
    }



    public function calculate_monthly_nur()
    {
        
        $NX = ($this->duration_min * $this->cells) / (60 * 24);
        $weekly_nur = ($NX * 100000) / ($this->total_network_cells * $this->days_of_month);
        $weekly_nur = number_format($weekly_nur, 2, '.', ',');
        return $weekly_nur;
    }
}
