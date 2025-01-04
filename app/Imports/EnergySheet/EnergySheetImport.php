<?php

namespace App\Imports\EnergySheet;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;

class EnergySheetImport implements WithMultipleSheets
{
    use Importable, WithConditionalSheets;

    /**
     * @param Collection $collection
     */
    public $week, $year;

    public function __construct($week, $year)
    {
        $this->week = $week;
        $this->year = $year;
    }


    public function conditionalSheets(): array
    {
        return [
            "Power" =>new PowerAlarmsImport($this->week, $this->year),
            "Down" =>new DownAlarmsImport($this->week, $this->year),
            "HT without power"=>new HighTempAlarmsImport($this->week,$this->year),
            "Power with gen"=>new GenDownAlarmsImport($this->week,$this->year)

        ];
    }
}
