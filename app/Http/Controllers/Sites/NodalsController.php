<?php

namespace App\Http\Controllers\Sites;

use App\Models\Nodal;
use Illuminate\Http\Request;
use App\Exports\sites\NodalsExport;
use App\Imports\Sites\NodalsImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class NodalsController extends Controller
{
    public function importNodals(Request $request)
    {
        $import=new NodalsImport();

        $validator=Validator::make($request->all(),["nodals" => 'required|mimes:csv,xlsx']);
        $validated=$validator->validated();

    

        if($validated)
        {
          
            try {
                Excel::import($import,  $validated["nodals"]);
                   
            
                return response()->json([
                    "message" => "inserted Succesfully",
                ]);
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $failures = $e->failures();
                $errors = [];
                $error = [];
    
                foreach ($failures as $failure) {
                    $error['row'] = $failure->row(); // row that went wrong
                    $error['attribute'] = $failure->attribute(); // either heading key (if using heading row concern) or column index
                    $error['errors'] = $failure->errors(); // Actual error messages from Laravel validator
                    $error['values'] = $failure->values(); // The values of the row that has failed.
                    array_push($errors, $error);
                }
                return response()->json([
                    "sheet_errors" => $errors,
                ], 422);
            }

        }
        else
        {
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);


            $this->throwValidationException(

                $request,
                $validator

            );

        }

       

       


    }

    public function exportNodals(Request $request)
    {
      
         return new NodalsExport();

    }
}
