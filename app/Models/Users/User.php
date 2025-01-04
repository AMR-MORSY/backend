<?php

namespace App\Models\Users;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Price;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Users\UserSession;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Notifications\Notifiable;
use App\Models\Modifications\Modification;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "remember_token",
        "email_verified_at",
        "rem_token_created_at"

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        "created_at",
        "updated_at",
        "email_verified_at",
        "rem_token_created_at"

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function session()
    {
        return $this->hasOne(UserSession::class, "user_id");
    }

    public function modifications()
    {
        return $this->hasMany(Modification::class,"action_owner");
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
               
                return ucwords($value);
            }
        );
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }


public function invoice()
{
    return $this->hasOne(Invoice::class);
}
}
