<?php

namespace App\Rules;

use App\Models\UnpricedItem;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class UnpricedItemCheckRule implements DataAwareRule,ValidationRule
{
    protected $data=[];
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $unpriced_item=UnpricedItem::where('item',$this->data['item'])->first();
        if($value==null && !isset($unpriced_item))
        {
            $fail('quantity is required');
        }
        
    }

    public function setData(array $data): static
    {
        $this->data = $data;
 
        return $this;
    }
}
