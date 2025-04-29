<?php

namespace App\Models\Instruments;

use App\Models\Sites\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeepData extends Model
{
    use HasFactory;

    protected $table = "site_deep_data";

    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded=[];
    protected $fillable = [
        'site_code',
        "on_air_date",
        "topology",
        "structure",
        "equip_room",
        "ntra_cluster",
        "care_ceo",
        "axsees",
        "serve_compound",
        "universities",
        "hot_spot",
        "ac1_type",
        "ac1_hp",
        "ac2_type",
        "ac2_hp",
        "network_type",
        "last_pm_date",
        "need_access_permission",
        "permission_type",
    
    ];
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, "site_code","site_code");
    }


    protected function careCeo(): Attribute
    {
        return Attribute::make(

            get: function ($value, $attributes) {
                if ($value == 0) {
                    return "No";
                } else {
                    return "Yes";
                }
            },
            set: function ($value) {
                if ($value == "No" or $value == null) {
                    return 0;
                } else {
                    return 1;
                }
            }

        );
    }
    protected function ntraCluster(): Attribute
    {
        return Attribute::make(

            get: function ($value) {
                if ($value == 0) {
                    return "No";
                } else {
                    return "Yes";
                }
            },
            set: function ($value) {
                if ($value == "No" or $value == null) {
                    return 0;
                } else {
                    return 1;
                }
            }

        );
    }
    protected function axsees(): Attribute
    {
        return Attribute::make(

            get: function ($value) {
                if ($value == 0) {
                    return "No";
                } else {
                    return "Yes";
                }
            },
            set: function ($value) {
                if ($value == "No" or $value == null) {
                    return 0;
                } else {
                    return 1;
                }
            }

        );
    }
    protected function serveCompound(): Attribute
    {
        return Attribute::make(

            get: function ($value) {
                if ($value == 0) {
                    return "No";
                } else {
                    return "Yes";
                }
            },
            set: function ($value) {
                if ($value == "No" or $value == null) {
                    return 0;
                } else {
                    return 1;
                }
            }

        );
    }
    protected function universities(): Attribute
    {
        return Attribute::make(

            get: function ($value) {
                if ($value == 0) {
                    return "No";
                } else {
                    return "Yes";
                }
            },
            set: function ($value) {
                if ($value == "No" or $value == null) {
                    return 0;
                } else {
                    return 1;
                }
            }

        );
    }
    protected function hotSpot(): Attribute
    {
        return Attribute::make(

            get: function ($value) {
                if ($value == 0) {
                    return "No";
                } else {
                    return "Yes";
                }
            },
            set: function ($value) {
                if ($value == "No" or $value == null) {
                    return 0;
                } else {
                    return 1;
                }
            }

        );
    }



    protected function needAccessPermission(): Attribute
    {
        return Attribute::make(

            get: function ($value) {
                if ($value == 0) {
                    return "No";
                } else {
                    return "Yes";
                }
            },
            set: function ($value) {
                if ($value == "No" or $value == null) {
                    return 0;
                } else {
                    return 1;
                }
            }

        );
    }
}
