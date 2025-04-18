<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationResource;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends ApiController
{
    public function index()
    {
        return NotificationResource::collection(Auth::user()->notifications);
    }

    public function show(string $language, DatabaseNotification $notification)
    {
        return NotificationResource::make($notification);
    }

    public function markAsRead(string $language, DatabaseNotification $notification)
    {
        $notification->markAsRead();

        return NotificationResource::make($notification);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return NotificationResource::collection(Auth::user()->notifications);
    }

    public function destroy(string $language, DatabaseNotification $notification)
    {
        $notification->delete();

        return $this->response
            ->message('notification deleted successfully')
            ->build();
    }

    public function destroyAll()
    {
        //  NOTE: not entirely sure if this is required but doesn't hurt to have it 
        return  DB::transaction(function () {
            Auth::user()->notifications()->delete();

            return $this->response
                ->message('all notifications deleted successfully')
                ->build();
        });
    }
}
