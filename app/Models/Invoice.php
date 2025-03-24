<?php

namespace App\Models;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modifications\Modification;
use App\Models\Modifications\Subcontractor;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;

    protected $table="invoices";

    protected $guarded=[];

    public function subcontractor():BelongsTo
    {
        return $this->belongsTo(Subcontractor::class,'subcontractor_id');
    }

    public function modifications():HasMany
    {
        return $this->hasMany(Modification::class);
    }

    public function file():HasOne
    {
        return $this->hasOne(File::class);
    }
}
