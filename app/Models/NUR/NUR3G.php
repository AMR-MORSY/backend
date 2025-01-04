<?php

namespace App\Models\NUR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NUR3G extends Model
{
    use HasFactory;
    protected $table="3g_nurs";
    protected $guarded=[];
    // public function getOzAttribute($value)
    // {
    //     return strtoupper($value);
    // }
    // public function getSubSystemAttribute($value)
    // {
    //     return strtoupper($value);
    // }
    protected function subSystem(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtoupper($value),
        );
    }
    protected function oz(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtoupper($value),
        );
    }
    protected function solution(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucwords($value),
        );
    }
    protected function office():Attribute
    {
        return Attribute::make(
            set: fn($value)=> strtolower($value),
            get: fn ($value)=>ucwords($value)

        );
    }
}
