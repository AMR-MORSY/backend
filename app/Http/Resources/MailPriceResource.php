<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MailPriceResource extends JsonResource
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
     
        "description"=>$this->description,
        "unit"=>$this->unit,
        "supply"=>$this->supply,
        "installation"=>$this->installation,
        "sup_inst"=>$this->sup_inst,
        "type"=>$this->type,
        // "quantity"=>$this->pivot->quantity,

        'quantity' => $this->whenPivotLoaded("mail_price_quotation", function () {/////////////////this attribute is not mail model attribute, this is a pivot table attribute, will be returned only in case of loading that table 
            return $this->pivot->quantity;
        }),
        'supply_price' => $this->whenPivotLoaded("mail_price_quotation", function () {/////////////////this attribute is not mail model attribute, this is a pivot table attribute, will be returned only in case of loading that table 
            return $this->pivot->supply_price;
        }),
        'install_price' => $this->whenPivotLoaded("mail_price_quotation", function () {/////////////////this attribute is not mail model attribute, this is a pivot table attribute, will be returned only in case of loading that table 
            return $this->pivot->install_price;
        }),
        'scope' => $this->whenPivotLoaded("mail_price_quotation", function () {/////////////////this attribute is not mail model attribute, this is a pivot table attribute, will be returned only in case of loading that table 
            return $this->pivot->scope;
        }),
        'item_price' => $this->whenPivotLoaded("mail_price_quotation", function () {/////////////////this attribute is not Price model attribute, this is a pivot table attribute, will be returned only in case of loading that table 
            return $this->pivot->item_price;
        }),
       
       
       
       
       
       ];
    }
}
