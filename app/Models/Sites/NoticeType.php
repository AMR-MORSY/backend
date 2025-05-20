<?php

namespace App\Models\Sites;

use App\Models\Sites\Notice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NoticeType extends Model
{
    use HasFactory;

    protected $table = 'notice_types';

    protected $guarded = [];
    public function notices():HasMany
    {
        return $this->hasMany(Notice::class, 'notice_type');
    }
}
