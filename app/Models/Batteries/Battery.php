<?php

namespace App\Models\Batteries;

use Carbon\Carbon;
use App\Models\Sites\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Battery extends Model
{
    protected $table="batteries";
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $guarded=[];

    use HasFactory;

    public function site()
    {
        return $this->belongsTo(Site::class,"site_code");
    }

    protected function installationDate():Attribute
    {
        return Attribute::make(
            set:function($value){
                if($value!=null)
                {
                    $newDate = Carbon::parse($value);
                    return  $newDate->format("Y-m-d");

                }
             

            }
        );

    }
    protected function theftCase():Attribute
    {
        return Attribute::make(
            set:function($value){
                if($value!=null)
                {
                    $newDate = Carbon::parse($value);
                    return  $newDate->format("Y-m-d");

                }
            }
        );

    }


  

}
