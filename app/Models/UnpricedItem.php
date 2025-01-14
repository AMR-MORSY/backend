<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnpricedItem extends Model
{
    use HasFactory;

    protected $table="unpriced_items";

    protected $guarded=[];
}
