<?php

namespace App\Http\Controllers\User;


use app;
use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use App\Models\Users\User;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Services\DateFormatter;
use App\Services\NUR\Durations;
use App\Models\Users\AccessToken;
use App\Models\Users\UserSession;
use App\Models\Users\Notification;

use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Traits\Admin\AuthAndAuthorization;
use Stevebauman\Location\Facades\Location;
use Illuminate\Notifications\DatabaseNotification;

class LoginController extends Controller
{
    use AuthAndAuthorization;


    public function notifications(Request $request)
    {
        $per_page = $request->query('per_page',5);
        // $page = $request->input('page',1);
        $user = User::find(auth()->user()->id);

        if ($user) {
            $notifications = $user->notifications()->paginate($per_page);

            $notifications->getCollection()->transform(function ($notification) {
                $notification->created_at = app(DateFormatter::class)
                    ->formatToUserTimezone($notification->created_at);
                return $notification;
            });


            return response()->json([
                "message" => 'success',
                "notifications" => $notifications

            ], 200);
        }

        abort(403);
    }


    public function markNotificationAsRead($notification)
    {
        $notification = DatabaseNotification::find($notification);
        $notification->markAsRead();
        // $user = Auth::user();
        // $notifications = $user->notifications;
        return response()->json([
            "message" => 'success',
            // "notifications" => $notifications
        ], 200);
    }
    public function allNotificationsAsRead()
    {
        $user =  Auth::user();

        foreach ($user->unreadNotifications as $notification) {
            $notification->markAsRead();
        }

        // $notifications = $user->notifications;


        return response()->json([
            "message" => 'success',
            // "notifications" => $notifications

        ], 200);
    }
    public function deleteNotification($notification)
    {
        $notification = DatabaseNotification::find($notification);
        $notification->delete();
        // $user =  Auth::user();
        // $notifications = $user->notifications;

        return response()->json([
            "message" => 'success',
            // "notifications" => $notifications
        ], 200);
    }

    public function deleteAllNotification()
    {
        $user =  Auth::user();
        // $user->notifications()->delete();
        foreach ($user->notifications as $notification) {
            $notification->delete();
        }

        return response()->json([
            "message" => 'success',
            "notifications" => []
        ], 200);
    }
    public function login(Request $request)

    {




        return $this->Authenticate($request->all());
    }
}
