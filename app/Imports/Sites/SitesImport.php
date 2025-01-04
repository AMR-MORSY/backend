<?php

namespace App\Imports\Sites;

use App\Models\Sites\Site;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;


HeadingRowFormatter::default('none');


class SitesImport implements ToModel, WithHeadingRow, WithValidation ,WithBatchInserts ,WithChunkReading,SkipsOnFailure
{
    use Importable,SkipsFailures ;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function rules(): array
    {
        return[
            "*.Site Code"=>["required","unique:sites,site_code","regex:/^([0-9a-zA-Z]{4,6}(up|UP))|([0-9a-zA-Z]{4,6}(ca|CA))|([0-9a-zA-Z]{4,6}(de|DE))$/"],
            "*.Site Name"=>["required", "regex:/^([0-9a-zA-Z_-]|\s){2,60}$/"],
            "*.RNC"=>["nullable", "regex:/^([0-9a-zA-Z_-]|\s){3,50}$/"],
            "*.BSC"=>["nullable", "regex:/^([0-9a-zA-Z_-]|\s){3,50}$/"],
            "*.Office"=>["nullable","regex:/^[0-9a-zA-Z_-]{3,50}$/"],
            "*.Type"=>["nullable","regex:/^Outdoor|Shelter|Micro$/"],
            "*.Severity"=>["nullable","regex:/^Gold|Silver|Bronze$/"],
            "*.Category"=>["nullable","regex:/^VIP|LDN|NDL|(VIP \+ NDL)|BSC|Normal$/"],
            "*.Sharing"=>["nullable","regex:/^Yes|No$/"],
            "*.Host"=>["nullable","regex:/^OG|VF|WE|ET$/"],
            "*.Gest"=>["nullable","regex:/^OG|VF|WE|ET$/"],
            "*.2G"=>["nullable","regex:/^(100)|[1-9]\d?$/"],
            "*.3G"=>["nullable","regex:/^(100)|[1-9]\d?$/"],
            "*.4G"=>["nullable","regex:/^(100)|[1-9]\d?$/"],
            "*.oz"=>["nullable","regex:/^Cairo South|Cairo East|Cairo North|GZ$/"],
            "*.zone"=>["nullable","regex:/^(Cairo)$/"],
            "*.status"=>["required","regex:/^On Air|Off Air$/"],
            
        ];
    }
    public function customValidationMessages()
{
    return [
        'Type.regex' => 'The site type must be (Outdoor|Micro|Shelter)',
        'Severity.regex' => 'The site severity must be (Gold|Selver|Bronze)',
        'Category.regex' => 'The site category must be (VIP|VIP + NDL|NDL|Normal|BSC|LDN)',
        'Sharing.regex' => 'The sharing status Either (Yes|No)',
        'Gest.regex' => 'should be one of (OG|VF|WE|ET)',
        'Host.regex' => 'should be one of (OG|VF|WE|ET)',
        "2G.regex"=>"Cells number from 1-100",
        "3G.regex"=>"Cells number from 1-100",
        "4G.regex"=>"Cells number from 1-100",
        "*oz.regex"=>"Operation Zone:(Cairo South|Cairo East|Cairo North|GZ)",
        "zone.regex"=>"Zone must be Cairo",
        "status.regex"=>"status either on air or off air"

    ];
}
    public function model(array $row)
    {
        return new Site([
            "site_code"=>$row['Site Code'],
            "site_name"=>$row['Site Name'],
            "BSC"=>$row['BSC'],
            "RNC"=>$row['RNC'],
            'office'=>$row['Office'],
            'type'=>$row['Type'],
            'category'=>$row['Category'],
            'severity'=>$row["Severity"],
            'sharing'=>$row['Sharing'],
            'host'=>$row['Host'],
            'gest'=>$row["Gest"],
            'oz'=>$row['oz'],
            'zone'=>$row['zone'],
            "2G_cells"=>$row["2G"],
            "3G_cells"=>$row["3G"],
            "4G_cells"=>$row["4G"],
            "status"=>$row["status"]
        ]);
    }
    public function batchSize(): int
    {
        return 100;
    }
    public function chunkSize(): int
    {
        return 1000;
    }
}

