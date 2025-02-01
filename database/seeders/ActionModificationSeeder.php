<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Modifications\Action;
use App\Models\Modifications\Modification;
use App\Models\Modifications\ActionModification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ActionModificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modifications = Modification::all();


        foreach($modifications as $modification)
        {
            if($modification->actions=="Adding Antennas")
            {
                $action=Action::where("name","Adding Antennas")->first();
                ActionModification::create([
                    "modification_id"=>$modification->id,
                    "action_id"=>$action->id
                ]);
            }
            elseif ($modification->actions=="Adding SA")
            {
                $action=Action::where("name","Adding SA")->first();
                ActionModification::create([
                    "modification_id"=>$modification->id,
                    "action_id"=>$action->id
                ]);
            }
            elseif ($modification->actions=="Antenna Swap")
            {
                $action=Action::where("name","Antenna Swap")->first();
                ActionModification::create([
                    "modification_id"=>$modification->id,
                    "action_id"=>$action->id
                ]);
            }
            elseif ($modification->actions=="Cable Trays")
            {
                $action=Action::where("name","Cable Trays")->first();
                ActionModification::create([
                    "modification_id"=>$modification->id,
                    "action_id"=>$action->id
                ]);
            }
            elseif ($modification->actions=="Changing Power Cable")
            {
                $action=Action::where("name","Changing Power Cable")->first();
                ActionModification::create([
                    "modification_id"=>$modification->id,
                    "action_id"=>$action->id
                ]);
            }
            elseif ($modification->actions=="Concrete Works")
            {
                $action=Action::where("name","Concrete Works")->first();
                ActionModification::create([
                    "modification_id"=>$modification->id,
                    "action_id"=>$action->id
                ]);
            }
            elseif ($modification->actions=="Extending Cables")
            {
                $action=Action::where("name","Extending Cables")->first();
                ActionModification::create([
                    "modification_id"=>$modification->id,
                    "action_id"=>$action->id
                ]);
            }
            elseif ($modification->actions=="Repair")
            {
                $action=Action::where("name","Repair")->first();
                ActionModification::create([
                    "modification_id"=>$modification->id,
                    "action_id"=>$action->id
                ]);
            }
            elseif ($modification->actions=="Retrofitting")
            {
                $action=Action::where("name","Retrofitting")->first();
                ActionModification::create([
                    "modification_id"=>$modification->id,
                    "action_id"=>$action->id
                ]);
            }
        }
    }
}
