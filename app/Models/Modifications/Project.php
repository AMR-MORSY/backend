<?php

namespace App\Models\Modifications;

use Illuminate\Database\Eloquent\Model;
use App\Models\Modifications\Modification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    protected $table="projects";
    protected $hidden=["created_at","updated_at"];
    protected $guarded=[];

    public function modifications():HasMany
    {
        return $this->hasMany(Modification::class);
    }
}
