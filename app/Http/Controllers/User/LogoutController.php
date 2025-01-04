<?php

namespace App\Http\Controllers\User;

use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        // Auth()->guard("web")->logout();
     $user=User::find(Auth::user()->id)   ;
     $user->tokens()->delete();
    }
}
