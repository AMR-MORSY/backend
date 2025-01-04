<?php

namespace App\Models\Users;

use Carbon\Carbon;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSession extends Model
{
    use HasFactory;
    protected $table="user_sessions";
    protected $guarded=[];

    public function getSessionEndAttribute($value)
    {
        $dt = Carbon::createFromFormat('Y-m-d H:i:s', $value);
        return $dt->toRfc7231String();  
    }
    public function getSessionStartAttribute($value)
    {
        $dt = Carbon::createFromFormat('Y-m-d H:i:s', $value);
        return $dt->toRfc7231String();  
    }


    public function user()
    {
        return $this->belongsTo(User::class,"user_id");
    }
}
