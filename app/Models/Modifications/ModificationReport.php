<?php

namespace App\Models\Modifications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModificationReport extends Model
{
    use HasFactory;

    protected $table="reported_modifications";

    protected $hidden=['created_at',"updated_at"];

    public function modifications():HasMany
    {
        return $this->hasMany(Modification::class);
    }
}
