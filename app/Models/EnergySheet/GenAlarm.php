<?php

namespace App\Models\EnergySheet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenAlarm extends Model
{
    use HasFactory;
    protected $table = "gen_down_alarms";
    protected $guarded = [];

   
}
