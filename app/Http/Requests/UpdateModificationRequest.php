<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateModificationRequest extends FormRequest
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
            
            "subcontractor" => ["required", "regex:/^OT|Alandick|Tri-Tech|Siatnile|Merc|GP|MBV|Systel|TELE-TECH|SAG|LM|HAS|MERG|H-PLUS|STEPS|GTE|AFRO|Benaya|EEC|Egypt Gate|Huawei|INTEGRA|Unilink|Red Tech|Tele-Trust|SAMA-TEL$/"],
            "actions" => ["required", "regex:/^Retrofitting|Antenna Swap|Repair|Adding SA|Change Power Cable|WE Sharing Panel|PT Ring|Adding Antennas|Extending Cables|Concrete Works|Cable Trays$/"],
            "description" => ["nullable", "string"],
            // "oz" => ['nullable', "regex:/^Cairo South|Cairo East|Cairo North,Giza$/"],
            //  "action_owner" => ["required", 'exists:users,id'],
            "request_date" => "required|date",
            "cw_date" => [" nullable", "date", "requiredIf:status,done", "after_or_equal:request_date"],
            "d6_date" => [" nullable", "date", "requiredIf:status,done", "after_or_equal:request_date"],
            "status" => ["required", "regex:/^waiting D6|done|in progress$/"],
            "requester" => ["required", "regex:/^Management Team|Civil Team|Maintenance|Radio|Rollout|Transmission|GA|Soc|Sharing team$/"],
            "project" => ["required", "regex:/^Site Dismantle|NTRA|Unsafe Existing|B2B|LTE|5G|Sharing|Site Security|Adding Sec|TDD|Power Modification|L1 Modification|Tx Modification|G2G|New Sites$/"],
            "est_cost" => "nullable|numeric",
            "final_cost" => ["nullable", "numeric", "requiredIf:status,done"],
            // "wo_code" => "nullable|string|max:20",
            "reported" => ["required", "regex:/^Yes|No$/"],
            "reported_at"=>["nullable","date","required_if:reported,Yes"]
        ];
    }

    
    private function dateFormat($date)
    {
        if (isset($date) && !empty($date)) {
            $newDate = Carbon::parse($date);
            return $newDate = $newDate->format("Y-m-d");
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
