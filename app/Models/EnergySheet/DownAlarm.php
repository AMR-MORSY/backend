<?php

namespace App\Models\EnergySheet;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DownAlarm extends Model
{
    use HasFactory;
    protected $table = "down_alarms";
    protected $guarded = [];

}
