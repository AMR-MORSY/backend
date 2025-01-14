<?php

namespace App\Rules;

use App\Models\Price;
use App\Models\UnpricedItem;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class UnpricedItemCheckRule implements ValidationRule
{
    // protected $item_id;
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

     protected $request;


     public function __construct($request)
     {
        $this->request=$request;
     }


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $index = explode('.', $attribute)[1];///////////// the attribute passed to the rule is "priceListItems.*.quantity". * is the index of the current attribute in the array which will be returned by explode function "the second array element"   
        $id = $this->request->input("priceListItems.{$index}.id");///////////////to get the value of this id attribute which of course has the same quantity index
        $priced_item=Price::find($id);
        
        if($priced_item)
        {
            $unpriced_item=UnpricedItem::where('item',$priced_item->item)->exists();
            if($value==0 && !$unpriced_item)
            {
                $fail('quantity is required');
            }
            

        }
        else{
            $fail('item not found');
            
        }
      
    }

    // public function setData(array $data): static
    // {
    //     $this->item_id = $data;
 
    //     return $this;
    // }
}
