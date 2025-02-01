<?php

namespace App\Models\Modifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ActionModification extends Pivot
{
  
    
    use HasFactory;
    protected $table="action_modification";
    public $incrementing = true;
    protected $guarded=[];
}
