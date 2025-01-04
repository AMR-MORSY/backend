<?php

namespace App\Services\NUR;

use App\services\NurHelpers\DurationMin\DurationMin;
use Illuminate\Validation\Rules\In;

class WeeklyNUR
{
   


    public static function calculate_NUR(int $duration_min,int $cells,int $total_technology_cells)

    {
        
        $NX = ($duration_min * $cells) / (60 * 24);
        $weekly_nur = ($NX * 100000) / ($total_technology_cells * 7);
        $weekly_nur = number_format($weekly_nur, 2, '.', ',');
        return $weekly_nur;
    }

    public static function calculateCombinedNUR($NUR,int $technology_cells,int $total_net_cells)
    {
        return ($NUR*$technology_cells)/$total_net_cells;

    }
}
