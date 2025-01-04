<?php

namespace App\Models;

use App\Models\Sites\Site;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nodal extends Model
{
    use HasFactory;
    protected $table="nodals";
    protected $guarded=[];

    public function site()
    {
        return $this->belongsTo(Site::class,"site_code");
    }

    public function cascades()
    {
        return $this->hasMany(Cascade::class,"nodal_code","nodal_code");
    }
}
