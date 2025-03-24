<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "subcontractor_id"=>$this->whenLoaded('subcontractor'),
            "file"=>$this->whenLoaded('file'),
            "invoice_date"=>$this->invoice_date ,
            "invoice_number"=>$this->invoice_number,
            "invoice_amount"=>$this->invoice_amount,
            "modification_amount"=>$this->modification_amount,
            "po_number"=>$this->po_number,
            "activity"=>$this->activity

        ];
    }
}
