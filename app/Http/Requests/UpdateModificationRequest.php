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
            
            "subcontractor" => ["required", "exists:subcontractors,id"],
            "actions" => ["required", "array"],
            "actions.*"=>['required','exists:actions,id'],
            "description" => ["nullable", "string",'regex:/^[a-zA-Z0-9\-_!@#$%^&*(),.?":{}\n\t|<> ]+$/'],//////regex for special chars, chars,numbers,spaces,underscore,dash
            "pending"=>['nullable','string','regex:/^[a-zA-Z0-9\-_!@#$%^&*(),.?":{}\n\t|<> ]+$/'],
            "request_date" => "required|date",
            "cw_date" => [" nullable", "date", "requiredIf:status,done", "after_or_equal:request_date"],
            "d6_date" => [" nullable", "date", "requiredIf:status,done", "after_or_equal:request_date"],
            "status" => ["required",'exists:modification_status,id'],
            "requester" => ["required", 'exists:requesters,id'],
            "project" => ["required",'exists:projects,id'],
            "est_cost" => "nullable|numeric",
            "final_cost" => ["nullable", "numeric", "requiredIf:status,done"],
           "reported" => ["required",'exists:reported_modifications,id'],
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
