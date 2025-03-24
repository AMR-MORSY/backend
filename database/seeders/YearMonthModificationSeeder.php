<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Modifications\Modification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class YearMonthModificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modifications = Modification::all();

        foreach ($modifications as $modification) {
            $date = Carbon::createFromFormat('Y-m-d', $modification->request_date);
            $year = $date->year;
            $month = $date->format('F');
            $modification->month=$month;
            $modification->year=$year;
            $modification->save();



        }
    }
}
