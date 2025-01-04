<?php

namespace App\Imports\Sites;

use App\Models\Nodal;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
HeadingRowFormatter::default('none');


class NodalsImport implements ToModel,WithHeadingRow,WithValidation,WithChunkReading,WithBatchInserts
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function rules():array
    {
        return [

            "*.nodal_code"=>["required","unique:nodals,nodal_code","regex:/^([0-9a-zA-Z]{4,6}(up|UP))|([0-9a-zA-Z]{4,6}(ca|CA))|([0-9a-zA-Z]{4,6}(de|DE))$/"],
            "*.nodal_name"=>["required", "regex:/^([0-9a-zA-Z_-]|\s){2,60}$/"],
            "*.site_code"=>["required","unique:nodals,site_code","regex:/^([0-9a-zA-Z]{4,6}(up|UP))|([0-9a-zA-Z]{4,6}(ca|CA))|([0-9a-zA-Z]{4,6}(de|DE))$/"],
            
        

        ];
    }
    public function model(array $row)
    {
        return new Nodal([

            "nodal_code"=>$row["nodal_code"],
            "nodal_name"=>$row['nodal_name'],
            "site_code"=>$row["site_code"],
           
          
        ]);
    }

    public function batchSize(): int
    {
        return 200;
    }
    public function chunkSize(): int
    {
        return 200;
    }
}
