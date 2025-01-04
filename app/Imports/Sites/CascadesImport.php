<?php

namespace App\Imports\Sites;

use App\Models\Cascade;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
HeadingRowFormatter::default('none');

class CascadesImport implements ToModel,WithHeadingRow,WithValidation,WithChunkReading,WithBatchInserts
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Cascade([
            "cascade_code"=>$row["cascade_code"],
            "cascade_name"=>$row['cascade_name'],
            "nodal_code"=>$row['nodal_code'],
        ]);
    }
    public function rules():array
    {
        return [
            "*.nodal_code"=>["required","regex:/^([0-9a-zA-Z]{4,6}(up|UP))|([0-9a-zA-Z]{4,6}(ca|CA))|([0-9a-zA-Z]{4,6}(de|DE))$/"],
            "*.cascade_code"=>["required","unique:cascades,cascade_code","regex:/^([0-9a-zA-Z]{4,6}(up|UP))|([0-9a-zA-Z]{4,6}(ca|CA))|([0-9a-zA-Z]{4,6}(de|DE))$/"],
            "*.cascade_name"=>["required", "regex:/^([0-9a-zA-Z_-]|\s){2,60}$/"],
        

        ];
        
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
