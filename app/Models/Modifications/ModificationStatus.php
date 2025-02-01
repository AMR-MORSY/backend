<?php

namespace App\Models\Modifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModificationStatus extends Model
{
    use HasFactory;
    protected $table="modification_status";

    protected $hidden=['created_at',"updated_at"];

    public function modifications():HasMany
    {
        return $this->hasMany(Modification::class);
    }
}
