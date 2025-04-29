<?php

namespace App\Http\Controllers\Sites;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sites\MuxPlan;
use Illuminate\Support\Facades\Validator;

class MuxPlanController extends Controller
{
    public function siteMuxPlans(Request $request)
    {
        $data=[
            'site_code'=>$request->query('site_code')
        ];
        $validator = Validator::make($data,[
            'site_code'=>['required','exists:sites,site_code']
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);
        } else {
            $validated = $validator->validated();

            $muxPlans=MuxPlan::where('ne_code',$validated['site_code'])->get();
            return response()->json(["message"=>'success',
            "muxPlans"=>$muxPlans],200);
        }
    }
}
