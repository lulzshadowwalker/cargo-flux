<?php

namespace App\Notifications;

use App\Support\PushNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DriverApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }
    /**
     * @return array<int,mixed>
     */
    public function via(object $notifiable): array
    {
        return [PushChannel::class, "database"];
    }

    public function toPush(object $notifiable): PushNotification
    {
        return new PushNotification(
            title: __("notifications.driver-approved.title"),
            body: __("notifications.driver-approved.body")
        );
    }

    /**
     * @return array<string,string|array|null>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            "title" => __("notifications.driver-approved.title"),
            "message" => __("notifications.driver-approved.body"),
        ];
    }
}
