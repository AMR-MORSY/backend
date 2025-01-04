<?php

namespace App\Http\Controllers\NUR;

use App\Models\NUR\NUR4G;
use Illuminate\Http\Request;
use App\Imports\NUR\NUR4GImport;
use App\Http\Controllers\Controller;
use App\Imports\NUR\NUR4G\Import4G;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class NUR4GController extends Controller
{
 
    public function store(Request $request)
    {
        $this->authorize("create",NUR4G::class);

        $validator = Validator::make($request->all(), ["week" => ['required', 'regex:/^(?:[1-9]|[1-4][0-9]|5[0-2])$/'], "year" => ['required', 'regex:/^2[0-9]{3}$/'], "cells"=>['required',"regex:/^(\d|[1-9]\d{1,5})(\.\d{2})?$/"],"Nur2G_sheet" => 'required|mimes:csv,xlsx',"total_net_cells"=>['required','gt:cells',"regex:/^(\d|[1-9]\d{1,5})(\.\d{2})?$/"]]);
        $validated = $validator->validated();
        if ($validated) {
            $nur=NUR4G::where('week',$validated['week'])->where('year',$validated['year'])->first();
           
            if($nur)
            {
                $week=$validated['week'];
                $year=$validated['year'];
                return response()->json([
                    "week_year" => "Week $week for year $year already exists",
                ], 422);
            }

            $import = new Import4G($validated['week'], $validated['year'],$validated['cells'],$validated['total_net_cells']);
            try {
               
                Excel::import($import, $request->file("Nur4G_sheet"));
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
        } else {

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
}
