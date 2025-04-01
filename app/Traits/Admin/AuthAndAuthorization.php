<?php

namespace App\Traits\Admin;

use Carbon\Carbon;
use App\Models\Users\User;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

trait AuthAndAuthorization
{
   

    public function hasVerifiedEmail($user)
    {
        if ($user->email_verified_at != null) {
            return true;
        }
        return false;
    }
    public function validateCredintials($credintials)
    {
        $validator = Validator::make($credintials, [
            "email" => "required|email|exists:users,email",
            "password" => ['required'],
            "timezone"=>["nullable",'string']

        ], [
            'email.exists' => 'Email does not exist!',
        ]);
        if ($validator->fails()) {
            return [

                "validated" => [],
                "errors" => $validator->getMessageBag()
            ];
        } else {
            $validated = $validator->validated();
            return [

                "validated" => $validated
            ];
        }
    }
    public function userAuthAttempt($validated)
    {
        if (!Auth::attempt(['email' => $validated["email"], 'password' =>  $validated["password"]])) {
            return response()->json([
                "message" => "invalid Credentials",

            ], 422);
        } else {

            $user = User::with('notifications')->where("id", Auth::user()->id)->first();
            $last_login=$user->login_at;
            if(isset($validated['timezone']) && $validated['timezone']!=null)
            {
                $date = Carbon::now()->setTimezone($validated['timezone']);
                $user->login_at=$date->format('Y-m-d H:i:s');
                $user->timezone=$validated['timezone'];
                $user->save();

            }
            else{
                $date = Carbon::now()->setTimezone(config('app.timezone'));
                $user->login_at=$date->format('Y-m-d H:i:s');
                $user->timezone=config('app.timezone');
                $user->save();

            }
         
            $token = $user->createToken($validated["email"]);
            $agent = new Agent();
            $platform = $agent->platform();
            $device = $agent->device();
            $desktop= $agent->isDesktop();
            $user_data["user"] = $user;
            $user_data["token"] = $token;
            $user_data["notifications"] = $user->notifications->count();
            $user_data["platform"]=$platform;
            $user_data["device"]=$device;
            $user_data["desktop"]=$desktop;
            $user_data['last_login']=$last_login;
            return response()->json(
                ["message" => "User loged in successfully", "user_data" => $user_data],

                200
            );
        }
    }
    public function Authenticate($credintials)

    {
        $validated = $this->validateCredintials($credintials)["validated"];

        if (count($validated) > 0) {
            $user = User::where("email", $validated["email"])->first();

            if ($this->hasVerifiedEmail($user)) {

                return   $this->userAuthAttempt($validated);
            } else {
                return response()->json([
                    "message" => "Account is not verified yet"
                ], 200);
            }
        }
        return response()->json([

            "errors" => $this->validateCredintials($credintials)["errors"]
        ], 422);
    }
}
