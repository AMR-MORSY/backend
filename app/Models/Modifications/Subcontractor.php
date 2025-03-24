<?php

namespace App\Models\Modifications;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modifications\Modification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subcontractor extends Model
{
    use HasFactory;
    protected $table="subcontractors";
    protected $hidden=["created_at","updated_at"];
    protected $guarded=[];

    public function modifications():HasMany
    {
        return $this->hasMany(Modification::class,'subcontractor');
    }

    public function invoices():HasMany
    {
        return $this->hasMany(Invoice::class,'subcontractor_id');
    }
}
