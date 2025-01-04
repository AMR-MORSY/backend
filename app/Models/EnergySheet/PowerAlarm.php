<?php

namespace App\Models\EnergySheet;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PowerAlarm extends Model
{
    use HasFactory;
    protected $table = "power_alarms";
    protected $guarded = [];

  
}
