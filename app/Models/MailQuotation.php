<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MailQuotation extends Pivot
{
    use HasFactory;
    public $incrementing = true;
    protected $guarded=[];

    protected $table="mail_price_quotation";
}
