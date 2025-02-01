<?php

namespace App\Rules;

use Closure;
use App\Models\Modifications\Action;
use App\Models\Modifications\Project;
use App\Models\Modifications\Requester;
use App\Models\Modifications\Subcontractor;
use App\Models\Modifications\ModificationReport;
use App\Models\Modifications\ModificationStatus;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class checkIfIdExists implements DataAwareRule, ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

     protected $data;

     public function setData($data)
     {
        $this->data=$data;

        return $this;
        
     }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->data['columnName']=="status")
        {
            if(!ModificationStatus::where('id',$value)->exists())
            {
                $fail('There is no status with this value');
            }
        }

        elseif($this->data['columnName']=="subcontractor")
        {
            if(!Subcontractor::where('id',$value)->exists())
            {
                $fail('There is no subcontractor with this value');
            }
        }
        elseif($this->data['columnName']=="reported")
        {
            if(!ModificationReport::where('id',$value)->exists())
            {
                $fail('There is no reporting with this value');
            }
        }
        elseif($this->data['columnName']=="actions")
        {
            if(!Action::where('id',$value)->exists())
            {
                $fail('There is no Action with this value');
            }
        }
        elseif($this->data['columnName']=="project")
        {
            if(!Project::where('id',$value)->exists())
            {
                $fail('There is no Project with this value');
            }
        }
        elseif($this->data['columnName']=="requester")
        {
            if(!Requester::where('id',$value)->exists())
            {
                $fail('There is no Requester with this value');
            }
        }
    }
}
