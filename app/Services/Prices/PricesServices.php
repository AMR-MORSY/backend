<?php

namespace App\Services\Prices;

use App\Http\Resources\PriceResource;
use App\Models\Price;

class PricesServices
{

    public function search(array $validated)
    {
        $search=$validated['search'];

        if($validated['searchBy']=="item")
        {
            $prices = Price::where('item',$search)->get();
            return PriceResource::collection($prices);
        


        }
        else{
            $prices = Price::where('description','like', "%$search%")->get();
            return PriceResource::collection($prices);


        }
      
       


    }
}