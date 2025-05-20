<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiteNoteRequest extends FormRequest
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
        $rules= [
            'site_code'=>['required','exists:sites,site_code'],
            'title'=>['required','string','max:100'],
            'description'=>['required','string'],
            'is_solved'=>['required','in:"Yes","No'],
            'notice_type'=>['required','exists:notice_types,id']

        ];

        if($this->isMethod('PUT'))
        {
            $rules['id']=['required','exists:notices,id'];
        }


        return $rules;
    }
}
