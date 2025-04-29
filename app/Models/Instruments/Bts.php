<?php

namespace App\Models\Instruments;

use App\Models\Sites\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bts extends Model
{
    use HasFactory;

    protected $table="bts_data";
    protected $hidden=['created_at','updated_at'];
    protected $guarded=[];

    public function site():BelongsTo
    {
        return $this->belongsTo(Site::class,"site_code","site_code");
    }

    
    protected function tdd():Attribute
    {
        
        return Attribute::make(
           
            get: function ( $value){
                if($value==0)
                {
                    return "No";


                }
                else{
                    return "Yes";
                    
                }

            },
            set: function ( $value){
                if($value=="No" or $value==null)
                {
                    return 0;


                }
                else{
                    return 1;
                    
                }

            }
            
        );

    }

}
