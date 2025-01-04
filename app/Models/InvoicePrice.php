<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class InvoicePrice extends Pivot
{
    use HasFactory;
    protected $table="invoice_price";

    protected $guarded=[];
}
