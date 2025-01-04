<?php

namespace App\Http\Controllers\User;

use App\Models\Users\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\SignupVerificationMail;
use App\Services\NUR\Durations;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email|unique:users",
            "password" =>  ['required', "regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", 'confirmed'],
            "name" => ["required", "max:50", "regex:/^[a-zA-Z]{3,}[a-zA-Z ]*$/"],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->getMessageBag(),

            ], 422);
        } else {
            $validated = $validator->validated();
            $user =  User::create([
                "name" => $validated["name"],
                "email" => $validated['email'],
                "password" => Hash::make($validated['password']),
                "remember_token" => Str::random(32),
                "rem_token_created_at"=>Carbon::now()

            ]);
            Mail::to($user)->send(new SignupVerificationMail($user->remember_token, $user->name));

            return response()->json([

                "message" => "Please check your e-mail and verifiy your accounnt"

            ], 200);
        }
    }
    public function validateSignUpCode($code)
    {
        $user = User::where("remember_token", $code)->first();
        $minutes_diff = Durations::DurationMin($user->rem_token_created_at, Carbon::now()); /////calculate the difference in minutes between signup code creation time and the time of activation request 

      
        if ($user) {
            if ($minutes_diff > 20) {//////////sign up code is valid for 10 minutes
                $user->rem_token_created_at = null;
                $user->save();
                return response()->json(["message" => "invalid Activation Code"], 422);
    
            }
            $user->email_verified_at = Carbon::now();
            $user->remember_token = null;
            $user->save();

            return response()->json(["message" => "User Account verified Successfully"], 200);
        } else {
            return response()->json(["message" => "invalid Activation Code"], 422);
        }
    }
    public function activateUserAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email|exists:users,email",
        ]);
        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->getMessageBag(),

            ], 422);
        } else {
            $validated = $validator->validated();
            $user = User::where("email", $validated["email"])->first();
            $user->remember_token = Str::random(32);
            $user->rem_token_created_at=Carbon::now();
            $user->save();
            Mail::to($user)->send(new SignupVerificationMail($user->remember_token, $user->name));


            return response()->json([

                "message" => "Please check your e-mail and verifiy your accounnt"

            ], 200);
        }
    }
}
