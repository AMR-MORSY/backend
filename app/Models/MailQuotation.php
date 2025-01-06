<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailQuotation extends Model
{
    use HasFactory;
    public $incrementing = true;
    protected $guarded=[];

    protected $table="mail_quotation";
}
