<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;

class NotificationsController extends Controller
{
    //
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate();
        return NotificationResource::collection($notifications);

    }

    public function stats(Request $request)
    {
        return response()->json([
           'unread_count'=>$request->user()->notification_count
        ]);
    }

    public function read(Request $request)
    {
//        $request->user()->readNotifications->markAsUnread();
        $request->user()->markAsRead();
        return response()->noContent();
    }

    public function readOne(DatabaseNotification  $notification, Request $request)
    {
        if($request->user()->unreadNotifications->contains($notification)){
            $notification->markAsRead();
        }
        return response()->noContent();
    }
}
