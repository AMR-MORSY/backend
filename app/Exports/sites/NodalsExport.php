<?php

namespace App\Exports\sites;

use ArrayIterator;
use App\Models\Nodal;
use App\Models\Sites\Site;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;





class NodalsExport implements FromCollection,Responsable,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection

    */
    use Exportable;

    private $fileName = 'Nodals.xlsx';
    private $writerType = Excel::XLSX;
    private $headers = [
        'Content-Type' => 'text/xlsx',
    ];
    public function collection()
    {
        $nodals=Nodal::all();
        $NodalsCollection=[];
        foreach($nodals as $nodal)
        {
           
           $site["nodal_name"]=$nodal->nodal_name;
            $site["nodal_code"]=$nodal->nodal_code;
            $directCascades=$nodal->cascades;
         

            $indirectCascades = [];


            $directCascadesArray = $directCascades->toArray();

            $indirectCascadesContainer = new ArrayIterator($directCascadesArray);
            $i = 0;
            while (count($indirectCascadesContainer) > 0) {

                $newNodal = Nodal::where("nodal_code", $indirectCascadesContainer[$i]["cascade_code"])->first();
                if (isset($newNodal)) {

                    foreach ($newNodal->cascades as $cascade) {
                        // $cascade_info=Site::where("site_code", $cascade["cascade_code"])->first();
                        // $cascade_category=$cascade_info->category;
                        // $cascade["category"]=$cascade_category;
                        array_push($indirectCascades, $cascade);
                        $indirectCascadesContainer->append($cascade);
                    }
                }




                $indirectCascadesContainer->offsetUnset($i);
                $i++;
            }
            $newDirectCascades = [];
            foreach ($directCascades as $cascade) {
                // $cascade_info=Site::where("site_code", $cascade["cascade_code"])->first();
                // $cascade_category=$cascade_info->category;
                // $cascade["category"]=$cascade_category;
                $newNodal = Nodal::where("nodal_code", $cascade["cascade_code"])->first();
                if (isset($newNodal)) {
                    // $CountCascades = $nodal->cascades->count();
                    // $cascade['countCascades'] = $CountCascades;
                    array_push($newDirectCascades, $cascade);
                } else {
                    array_push($newDirectCascades, $cascade);
                }
            }
            $site["count_cascades"]=collect($directCascades)->count()+collect($indirectCascades)->count();


            array_push($NodalsCollection,$site);


        }

        return $NodalsCollection=collect($NodalsCollection);
       
        
    }
    public function headings(): array
    {
        return [
           
            
            'nodal_code',
            "nodal_name",
            "count_cascades",
            
              
        ];
    }
}
