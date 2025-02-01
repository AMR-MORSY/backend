<?php

namespace Database\Seeders;

use App\Models\Modifications\Subcontractor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubcontractorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcontractors= ["OT","Alandick","Tri-Tech","Siatnile","Merc","GP","MBV","Systel","TELE-TECH","SAG","LM","HAS","MERG","H-PLUS","STEPS","GTE","AFRO","Benaya","EEC","Egypt Gate","Huawei","INTEGRA","Unilink","Red Tech","Tele-Trust","SAMA-TEL"];
        foreach($subcontractors as $subcontractor)
        {
            Subcontractor::create([
                "name"=>$subcontractor
            ]);
        }
    }
}
