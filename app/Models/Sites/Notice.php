<?php

namespace App\Models\Sites;

use App\Services\DateFormatter;
use App\Models\Sites\NoticeType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Notice extends Model
{
    use HasFactory;

    protected $table = 'notices';
    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_code', 'site_code');
    }

    public function noticeType(): BelongsTo
    {
        return $this->belongsTo(NoticeType::class, 'notice_type');
    }


    public function isSolved(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value == 0) {
                    return "No";
                }
                return "Yes";
            },
             set: function ($value) {
                if ($value == 'No') {
                    return 0;
                }
                return 1;
            }
        
        );
    }
}
