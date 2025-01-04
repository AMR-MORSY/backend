<?php

namespace App\Models;

use App\Models\Users\User;
use App\Models\PriceQuotation;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modifications\Modification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Price extends Model
{
    use HasFactory;

    protected $table = "prices";
    protected $guarded = [];
    protected $hidden = ["updated_at", "created_at"];


    public function quotations(): BelongsToMany
    {
        return $this->belongsToMany(Quotation::class)->using(PriceQuotation::class)->withPivot('quantity','item_price',"supply_price","install_price",'item_type');
    }
}
