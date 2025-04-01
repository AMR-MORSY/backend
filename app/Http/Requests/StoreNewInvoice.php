<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewInvoice extends FormRequest
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
           "subcontractor_id"=>["required", 'exists:subcontractors,id'],
            "invoice"=>['required', 'file','mimes:pdf','max:2048'],
            "invoice_date"=>[ 'required','date'] ,
            "invoice_number"=>[ 'required','integer'],
            "work_orders"=>['required','array'],
            "work_orders.*"=>['required','exists:modifications,wo_code'],
            "invoice_amount"=>['required'],
           
            "po_number"=>['required','integer'],
            "activity"=>['required']
        ];
    }
}
