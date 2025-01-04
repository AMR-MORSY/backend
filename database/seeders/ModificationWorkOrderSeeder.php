<?php

namespace Database\Seeders;

use App\Models\Modifications\Modification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModificationWorkOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modifications=Modification::all();
        foreach($modifications as $modification)
        {
            $modification->wo_code="CS-00".$modification->id;
            $modification->save();
        }
    }
}
