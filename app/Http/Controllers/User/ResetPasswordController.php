<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Users\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Mail\ResetPasswordMailable;
use App\Models\Users\PasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function sendToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => 'required|email'

        ]);
        $validated = $validator->validated();
        if ($validator->fails()) {
            return response()->json([
                $validator->getMessageBag(),
            ], 422);
        } else {
            $user = User::where('email', $validated['email'])->first();

            if ($user) {
                if ($user->email_verified_at != null) {
                    $token = Str::random(32);
                    $url = $request->host();
                    Mail::to($user)->send(new ResetPasswordMailable($token, $url));
                    $password_reset = new PasswordReset();
                    $password_reset->email = $user->email;
                    $password_reset->token = $token;
                    $password_reset->save();
                    return response()->json([
                        "message"=>"success"

                    ],200);

                }
                $emailError["email"] = "Account is not verified yet";

                return response()->json([
                    "errors" => $emailError,

                ],422);
            } else {

                $emailError["email"] = "Email does not exist";

                return response()->json([
                    "errors" => $emailError,
                ], 422);
            }
        }
    }
    public function validateToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "token" => 'required'

        ]);


        if ($validator->fails()) {
            return response()->json([
                $validator->getMessageBag(),
            ], 422);
        } else {
            $validated = $validator->validated();
            $password_reset = PasswordReset::where('token', $validated['token'])->first();
        }


        if (!$password_reset) {
            return response()->json([
                "error" => "invalid Token"
            ], 200);
        } else {

            $user = User::where("email", $password_reset->email)->first();
            $user->email_verified_at = Carbon::now();
            $user->remember_token = null;

            $user->save();
            return response()->json($user, 200);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "password" => ['required', 'string', "regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", 'confirmed'],
            "user_id" => ['required', "exists:users,id"]

        ]);
        if ($validator->fails()) {
            return response()->json([
                "errors" =>  $validator->getMessageBag()->toArray(),
            ], 422);
        } else {
            $validated = $validator->validated();
            $user = User::find($validated["user_id"]);
            $user->password = bcrypt($request->input('password'));
            $user->save();

            Auth::attempt(['email' => $user->email, 'password' => $request->input('password')]);

            $password_reset = PasswordReset::where("email", $user->email);
            $password_reset->delete();
            $token = $request->user()->createToken($user->email);
            $user_data["user"] = $user;
            $user_data["token"] = $token;
            return response()->json(
                ["message" => "User loged in successfully", "user_data" => $user_data],

                200
            );
        }
    }
}
