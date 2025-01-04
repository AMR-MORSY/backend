<?php

namespace App\Http\Resources;

use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceQuotationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    
    public function toArray(Request $request): array
    {
      return  [
            "id" => $this->id,
            "quantity" => $this->quantity,
            "description"=>Price::find($this->price_id)->first()->description,
        ];
    }
}
