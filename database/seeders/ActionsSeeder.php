<?php

namespace Database\Seeders;

use App\Models\Modifications\Action;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcontractors= ["Retrofitting","Antenna Swap","Repair","Adding SA","Changing Power Cable","WE Sharing Panel","PT Ring","Adding Antennas","Extending Cables","Concrete Works","Cable Trays","RRUs Relocation","Site Dismantle","Cage Installation","Adding Mast","Dismantling Cabinets","Relocating Power Meter"];
        foreach($subcontractors as $subcontractor)
        {
            Action::create([
                "name"=>$subcontractor
            ]);
        }
    }
}
