<?php

namespace App\Models\EnergySheet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HighTempAlarm extends Model
{
    use HasFactory;
    protected $table = "high_temp_alarms";
    protected $guarded = [];

   
}
