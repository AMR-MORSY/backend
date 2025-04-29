<?php

namespace App\Models\Instruments;

use App\Models\Sites\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Power extends Model
{
    use HasFactory;

    protected $table = "power_data";
    protected $hidden = ['created_at', 'updated_at'];
  
    protected $guarded=['id','created_at','updated_at'];


    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, "site_code","site_code");
    }
}
