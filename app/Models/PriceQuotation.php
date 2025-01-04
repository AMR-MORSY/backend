<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PriceQuotation extends Pivot
{
    use HasFactory;
    public $incrementing = true;

 

    // protected $table="price_quotation";

    protected $guarded=[];
}
