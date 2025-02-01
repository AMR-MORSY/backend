<?php

namespace App\Models\Modifications;

use App\Models\Price;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Sites\Site;
use App\Models\Users\User;

use Spatie\Activitylog\LogOptions;
use App\Models\Modifications\Action;
use App\Models\Modifications\Project;
use App\Models\Modifications\Requester;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modifications\Subcontractor;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Modifications\ActionModification;
use App\Models\Modifications\ModificationReport;
use App\Models\Modifications\ModificationStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Modification extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = "modifications";
    protected $guarded = [];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, "site_code", "site_code");
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(["*"])
            ->useLogName('modifications');
        // Chain fluent methods for configuration options
    }

    public function user()
    {
        return $this->belongsTo(User::class, "action_owner");
    }

    // public function reported(): Attribute
    // {
    //     return Attribute::make(
    //         set: function ($value) {
    //             if ($value == "Yes") {
    //                 return 1;
    //             }
    //             return 0;
    //         },
    //         get: function ($value) {
    //             if ($value == 1) {
    //                 return "Yes";
    //             }
    //             return "No";
    //         }
    //     );
    // }


    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }


    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function subcontract():BelongsTo
    {
        return $this->belongsTo(Subcontractor::class,'subcontractor');
    }
    public function request():BelongsTo
    {
        return $this->belongsTo(Requester::class,'requester');
    }
    public function proj():BelongsTo
    {
        return $this->belongsTo(Project::class,'project');
    }
    public function actions():BelongsToMany
    {
        return $this->belongsToMany(Action::class)->using(ActionModification::class);
    }

    public function report():BelongsTo
    {
        return $this->belongsTo(ModificationReport::class,'reported');
    }

    public function state():BelongsTo
    {
        return $this->belongsTo(ModificationStatus::class,'status');
    }
}
