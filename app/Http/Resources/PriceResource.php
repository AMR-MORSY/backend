<?php

namespace App\Http\Resources;

use App\Models\Price;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
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
        "item"=>$this->item,
        "description"=>$this->description,
        "unit"=>$this->unit,
        "supply"=>$this->supply,
        "installation"=>$this->installation,
        "sup_inst"=>$this->sup_inst,
        "type"=>$this->type,
        // "quantity"=>$this->pivot->quantity,

        'quantity' => $this->whenPivotLoaded("price_quotation", function () {/////////////////this attribute is not Price model attribute, this is a pivot table attribute, will be returned only in case of loading that table 
            return $this->pivot->quantity;
        }),
        'supply_price' => $this->whenPivotLoaded("price_quotation", function () {/////////////////this attribute is not Price model attribute, this is a pivot table attribute, will be returned only in case of loading that table 
            return $this->pivot->supply_price;
        }),
        'install_price' => $this->whenPivotLoaded("price_quotation", function () {/////////////////this attribute is not Price model attribute, this is a pivot table attribute, will be returned only in case of loading that table 
            return $this->pivot->install_price;
        }),
        'item_type' => $this->whenPivotLoaded("price_quotation", function () {/////////////////this attribute is not Price model attribute, this is a pivot table attribute, will be returned only in case of loading that table 
            return $this->pivot->item_type;
        }),
        'item_price' => $this->whenPivotLoaded("price_quotation", function () {/////////////////this attribute is not Price model attribute, this is a pivot table attribute, will be returned only in case of loading that table 
            return $this->pivot->item_price;
        }),
       
       
       
       
       
       ];
    }
}
