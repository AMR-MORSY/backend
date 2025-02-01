<?php

namespace Database\Seeders;

use App\Models\Modifications\Requester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequestersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcontractors= ["Site Management","Civil Team","Maintenance","Radio","Rollout","Transmission","GA","Soc","Sharing team"];
        foreach($subcontractors as $subcontractor)
        {
            Requester::create([
                "name"=>$subcontractor
            ]);
        }
    }
}
