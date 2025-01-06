<?php

namespace App\Models;

use App\Models\Price;
use App\Models\PriceQuotation;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modifications\Modification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quotation extends Model
{
    use HasFactory;

    protected $table="quotations";

    protected $guarded=[];

    // protected $with = ['prices'];

    public function modification():BelongsTo
    {
        return $this->belongsTo(Modification::class);
    }

    public function prices():BelongsToMany
    {
        return $this->belongsToMany(Price::class)->using(PriceQuotation::class)->withPivot('quantity','item_price',"supply_price","install_price",'scope');/////we have to include any attribute in the pivot table which you want to retrieve when BELONGS TO MANY relationship is loaded
    }

    public function mailPrices():BelongsToMany
    {
        return $this->belongsToMany(MailPrice::class)->using(MailQuotation::class)->withPivot('quantity','item_price',"supply_price","install_price",'scope');/////we have to include any attribute in the pivot table which you want to retrieve when BELONGS TO MANY relationship is loaded
    }
    
}
