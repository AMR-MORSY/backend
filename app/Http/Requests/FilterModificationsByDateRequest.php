<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class FilterModificationsByDateRequest extends FormRequest
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
          
            "from_date"=> ["nullable", 'date','required_if:to_date,null'],
            "to_date"=> ["nullable","date",'required_if:from_date,null',"after_or_equal:from_date"],
            "date_type"=>['required','string',"regex:/^d6_date|cw_date|request_date$/"],
        
        ];
    }

    
    private function dateFormat($date)
    {
        if ($date!="no date") {
            $newDate = Carbon::parse($date);
            return  $newDate->format("Y-m-d");
         
        } else {
            return null;
        }
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'to_date' => $this->dateFormat($this->to_date),
            "from_date" => $this->dateFormat($this->from_date),
            "date_type"=>$this->date_type
           
        ]);
    }
}
