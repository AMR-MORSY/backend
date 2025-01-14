<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceQuotation extends Pivot
{
    use HasFactory;
    public $incrementing = true;

 

    // protected $table="price_quotation";

    protected $guarded=[];

    protected function quantity(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if($value=='')
                {
                    return 0.00;

                }
                return $value;
               
            }
        );
    }
}
