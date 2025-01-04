<?php

namespace App\Http\Controllers\Prices;

use App\Models\Price;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Prices\PricesServices;
use Illuminate\Support\Facades\Validator;



class PriceController extends Controller
{

    protected $pricesServices;

    public function __construct(PricesServices $pricesServices)
    {
        $this->pricesServices = $pricesServices;
    }

    public function search(Request $request)
    {


        $validator = Validator::make($request->all(), ["search" => ['required', 'regex:/^.{1,50}$/'],"searchBy"=>['required',"in:item,description"]]);


        if ($validator->fails()) {
            return response()->json(
                [
                    "errors" => $validator->getMessageBag()->toArray(),

                ],
                422
            );
            $this->throwValidationException(


                $validator

            );
        } else {
            $validated = $validator->validated();

            $prices = [];
            if ($request->user()->can('viewPriceListItems', Price::class)) {
                $prices = $this->pricesServices->search($validated);
            } else {
                abort(403);
            }
             if (count($prices)> 0) {
                return response( [
                    "message" => "success",
                    "priceList" => $prices
                ],
                200);
             } else {
                return response()->json([
                    "message" => 'No data found'
                ],200);
            }

          

           
        }
    }
}
