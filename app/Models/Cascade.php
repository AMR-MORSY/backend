<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cascade extends Model
{
    use HasFactory;
    protected $table="cascades";
    protected $guarded=[];

    public function nodal()
    {
        return $this->belongsTo(Nodal::class,"nodal_code","nodal_code");
    }
}
