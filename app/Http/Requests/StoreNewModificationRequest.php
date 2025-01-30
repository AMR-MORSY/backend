<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreNewModificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
          
            "site_code" => "required|exists:sites,site_code",
            "subcontractor" => ["required", "regex:/^OT|Alandick|Tri-Tech|Siatnile|Merc|GP|MBV|Systel|TELE-TECH|SAG|LM|HAS|MERG|H-PLUS|STEPS|GTE|AFRO|Benaya|EEC|Egypt Gate|Huawei|INTEGRA|Unilink|Red Tech|Tele-Trust|SAMA-TEL$/"],
            "actions" => ["required", "regex:/^Retrofitting|Antenna Swap|Repair|Adding SA|Change Power Cable|WE Sharing Panel|PT Ring|Adding Antennas|Extending Cables|Concrete Works|Cable Trays|RRUs Relocation|Site Dismantle|Cage Installation|Adding Mast|Dismantling Cabinets|Relocating Power Meter$/"],
            "description" => ["nullable", "string"],
            "request_date" => "required|date",
            "cw_date" => [" nullable", "date", "requiredIf:status,done", "after_or_equal:request_date"],
            "d6_date" => [" nullable", "date", "requiredIf:status,done", "after_or_equal:request_date"],
            "status" => ["required", "regex:/^waiting D6|done|in progress$/"],
            "requester" => ["required", "regex:/^Management Team|Civil Team|Maintenance|Radio|Rollout|Transmission|GA|Soc|Sharing team$/"],
            "project" => ["required", "regex:/^Site Dismantle|NTRA|Unsafe Existing|B2B|LTE|5G|Sharing|Site Security|Adding Sec|TDD|Power Modification|L1 Modification|Tx Modification|G2G|New Sites$/"],
            "est_cost" => "nullable|numeric",
            "final_cost" => ["nullable", "numeric", "requiredIf:status,done"],
           "reported" => ["required", "regex:/^Yes|No$/"],
           "reported_at"=>["nullable","date","required_if:reported,Yes"]
        ];
    }


    private function dateFormat($date)
    {
        if (isset($date) && !empty($date)) {
            $newDate = Carbon::parse($date);
            return  $newDate->format("Y-m-d");
         
        } else {
            return null;
        }
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'request_date' => $this->dateFormat($this->request_date),
            "cw_date" => $this->dateFormat($this->cw_date),
            "d6_date" => $this->dateFormat($this->d6_date),
            "reported_at"=>$this->dateFormat($this->reported_at)
        ]);
    }
}
