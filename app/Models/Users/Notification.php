<?php 

// namespace App\Models\Users;

// use Carbon\Carbon;
// use App\Models\Users\User;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Casts\Attribute;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Illuminate\Database\Eloquent\Factories\HasFactory;

// class Notification extends Model
// {
//     use HasFactory;

//     protected $table = "notifications";

  
   
  
//     public function getCreatedAtAttribute($value)
//     {
//         if (auth()->check()) {
//             return Carbon::parse($value)->timezone(auth()->user()->timezone ?? config('app.timezone'));
//         }
//         return $value;
//     }
  
// }
