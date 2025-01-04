<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\PriceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       
        return [
            "id"=>$this->id,
            "modification"=>new ModificationResource($this->modification),
            "user"=>$this->user,
            "prices"=>PriceResource::collection($this->prices),
        ];
       
    }
}
