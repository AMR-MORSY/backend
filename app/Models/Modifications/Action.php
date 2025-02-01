<?php

namespace App\Models\Modifications;

use Illuminate\Database\Eloquent\Model;
use App\Models\Modifications\Modification;
use App\Models\Modifications\ActionModification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Action extends Model
{
    use HasFactory;
    protected $table="actions";
    protected $hidden=["created_at","updated_at"];
    protected $guarded=[];

    public function modifications():BelongsToMany
    {
        return $this->belongsToMany(Modification::class)->using(ActionModification::class);
    }
}
