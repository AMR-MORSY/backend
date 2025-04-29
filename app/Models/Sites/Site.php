<?php

namespace App\Models\Sites;

use App\Models\Nodal;
use App\Models\Sites\MuxPlan;
use App\Models\Transmission\WAN;
use App\Models\Batteries\Battery;
use App\Models\Instruments\Bts;
use App\Models\Instruments\DeepData;
use App\Models\Instruments\Power;
use App\Models\Transmission\XPIC;
use Spatie\Activitylog\LogOptions;
use App\Models\Instruments\Microwave;
use App\Models\Instruments\Rectifier;
use App\Models\Instruments\Instrument;
use App\Models\Transmission\IP_traffic;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modifications\Modification;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends Model
{
   
    use HasFactory;
    protected $table = "sites";
    protected $guarded = [];
    protected $hidden = ["created_at", "updated_at"];

   

    public function rectifier():HasOne
    {
      return  $this->hasOne(Rectifier::class,"site_code","site_code");
    }
    public function microwave():HasOne
    {
      return  $this->hasOne(Microwave::class,"site_code","site_code");
    }
    public function power():HasOne
    {
      return  $this->hasOne(Power::class,"site_code","site_code");
    }
    public function bts():HasOne
    {
      return  $this->hasOne(Bts::class,"site_code","site_code");
    }
    public function deepData():HasOne
    {
      return  $this->hasOne(DeepData::class,"site_code","site_code");
    }
    public function muxPlans():HasMany
    {
        return $this->hasMany(MuxPlan::class,'ne_code');
    }
    public function nodal()
    {
        return $this->hasOne(Nodal::class, "site_code","site_code");
    }
    public function modifications():HasMany
    {
        return $this->hasMany(Modification::class, "site_code","site_code");
    }
    public function wans()
    {
        return $this->hasMany(WAN::class, "site_code","site_code");
    }
    public function xpics()
    {
        return $this->hasMany(XPIC::class, "site_code","site_code");
    }
    public function ip_traffics()
    {
        return $this->hasMany(IP_traffic::class, "site_code","site_code");
    }
  
   
    protected function siteCode(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return strtoupper($value);
            }
        );
    }

    protected function siteName(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return strtoupper($value);
            }
        );
    }


    public function batteries()
    {
        return $this->hasMany(Battery::class, "site_code","site_code");
    }
}
