<?php

namespace App\Http\Resources;

use App\Models\Modifications\Modification;
use App\Models\Sites\Site;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            "site_code" => $this->site_code,
            "subcontractor" => $this->subcontractor,
            "site"=>$this->site,
            "actions" => $this->actions,
            "description" => $this->description,
            "oz" => $this->oz,
            "action_owner" => User::find($this->action_owner),
            "request_date" => $this->request_date,
            "cw_date" => $this->cw_date,
            "d6_date" => $this->d6_date,
            "status" => $this->status,
            "requester" => $this->requester,
            "project" =>$this->project,
            "est_cost" => $this->est_cost,
            "final_cost" =>$this->final_cost,
             "wo_code" => $this->wo_code,
            "reported" => $this->reported,
            "reported_at"=>$this->reported_at
           
        ];
    }
}
