<?php

namespace Database\Seeders;

use App\Models\Modifications\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcontractors= ["Site Dismantle","NTRA","Unsafe Existing","B2B","LTE","5G","Sharing","Site Security","Adding Sec","TDD","Power Modification","L1 Modification","Tx Modification","G2G","New Sites"];
        foreach($subcontractors as $subcontractor)
        {
            Project::create([
                "name"=>$subcontractor
            ]);
        }
    }
}
