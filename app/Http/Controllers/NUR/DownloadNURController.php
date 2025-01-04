<?php

namespace App\Http\Controllers\NUR;

use Illuminate\Http\Request;
use App\Exports\NUR\NUR2GExport;
use App\Exports\NUR\NUR3GExport;
use App\Exports\NUR\NUR4GExport;
use App\Http\Controllers\Controller;
use App\Models\NUR\NUR2G;
use Illuminate\Support\Facades\Validator;

class DownloadNURController extends Controller
{
   
     
    public function NUR2G(Request $request)
    {
        $this->authorize("viewAny",NUR2G::class);
        $validator = Validator::make($request->all(), ["site_code" => ['required']]);
        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);
        } else {
        $validated = $validator->validated();
        return new NUR2GExport($validated["site_code"]);
       
        }

    }
    public function NUR3G(Request $request)
    {
        $this->authorize("viewAny",NUR3G::class);
        $validator = Validator::make($request->all(), ["site_code" => 'required']);
        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);
        } else {
        $validated = $validator->validated();
        return new NUR3GExport($validated["site_code"]);
       
        }

        
    }
    public function NUR4G(Request $request)
    {
        $this->authorize("viewAny",NUR4G::class);
        $validator = Validator::make($request->all(), ["site_code" => ['required']]);
        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);
        } else {
        $validated = $validator->validated();
        return new NUR4GExport($validated["site_code"]);
       
        }

        
    }
}
