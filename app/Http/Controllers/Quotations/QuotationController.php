<?php

namespace App\Http\Controllers\Quotations;

use App\Models\Quotation;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\PriceResource;
use App\Http\Resources\QuotationResource;
use Illuminate\Support\Facades\Validator;
use App\Imports\Quotation\QuotationImport;
use App\Models\Modifications\Modification;
use App\Http\Resources\PriceQuotationResource;
use App\Services\Quotations\QuotationsServices;
use League\CommonMark\Extension\SmartPunct\QuoteProcessor;

class QuotationController extends Controller
{
    protected $quotationsServices;

    public function __construct(QuotationsServices $quotationsServices)
    {
        $this->quotationsServices = $quotationsServices;
    }

    public function findQuotationBelongsToModification(Request $request, Modification $modification)
    {
        if ($modification->oz == "Cairo South" && $request->user()->can('viewCairoSouthSiteModifications', Modification::class)) {
            $quotation = $modification->quotation;
        } else if ($modification->oz == "Giza" && $request->user()->can('viewGizaSiteModifications', Modification::class)) {
            $quotation = $modification->quotation;
        } else if ($modification->oz == "Cairo North" && $request->user()->can('viewCairoNorthSiteModifications', Modification::class)) {
            $quotation = $modification->quotation;
        } else if ($modification->oz == "Cairo East" && $request->user()->can('viewCairoEastSiteModifications', Modification::class)) {
            $quotation = $modification->quotation;
        } else {
            abort(403);
        }

        if (isset($quotation) && $quotation != null) {
            return response()->json([
                "message" => "success",
                "quotation" => new QuotationResource($quotation),


            ], 200);
        }
        else{
            return response()->json([
                "message"=>'No quotation'
            ],200);
        }
    }

    public function uploadQuotation(Request $request )
    {
        $validator = Validator::make($request->all(), [
            "quotation" => ['required', "file", 'mimes:xlx,xlsx,csv', 'max:1048'],
            "id"=>['required','exists:modifications,id']
           
        ]);
        if ($validator->fails()) {

            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);


            $this->throwValidationException(


                $validator

            );
        } else {
            $validated = $validated = $validator->validated();
         
            $quotation=Quotation::create([
                "modification_id"=>$validated['id'],
                "user_id"=>$request->user()->id,

            ]);
       
            $import=new QuotationImport($quotation['id']);
            try {
               
                Excel::import($import, $request->file("quotation"));
                return response()->json([
                    "message" => "inserted Succesfully",
                ]);
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $quotation->delete();
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

       

    }
}
