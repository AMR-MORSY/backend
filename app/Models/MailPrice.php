<?php

namespace App\Models;

use App\Models\Quotation;
use App\Models\MailQuotation;
use App\Models\PriceQuotation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MailPrice extends Model
{
    use HasFactory;

    protected $table="mail_prices";

    public function quotations(): BelongsToMany
    {
        return $this->belongsToMany(Quotation::class)->using(MailQuotation::class)->withPivot('quantity','item_price',"supply_price","install_price",'scope');
    }
}
