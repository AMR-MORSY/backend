<?php

namespace App\Models\Sites;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MuxPlan extends Model
{
    use HasFactory;

    protected $table="mux_plans";

    protected $hidden=['created_at','updated_at'];

    public function site():BelongsTo
    {
        return $this->belongsTo(Site::class,'site_code');
    }
}
