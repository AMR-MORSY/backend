<?php

namespace App\Models\Transmission;

use Carbon\Carbon;
use App\Models\Sites\Site;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class XPIC extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table="xpic";
    protected $guarded=[];
    protected $hidden=["created_at","updated_at"];
    public function site()
    {
        return $this->belongsTo(Site::class,"site_code","site_code"); 
    }

    protected function clearanceDate(): Attribute
    {
        return Attribute::make(
           
            set: function ($value) {
                if ($value=="") {
                   return null;
                }
                else{
                    $newDate = Carbon::parse($value);
                    return  $newDate->format("Y-m-d");
                }
            }
        );
    }
    protected function reportingDate():Attribute
    {
        return Attribute::make(
            set:function($value){
                $newDate = Carbon::parse($value);
                return  $newDate->format("Y-m-d");

            }
        );

    }
    protected function siteCode(): Attribute
    {
        return Attribute::make(
            get: function($value){
              return strtoupper($value);
            }
        );
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(["*"])
        ->useLogName('XPIC');
        // Chain fluent methods for configuration options
    }
}
