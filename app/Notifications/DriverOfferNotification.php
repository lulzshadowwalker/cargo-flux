<?php

namespace App\Notifications;

use App\Support\PushNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DriverOfferNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via(object $notifiable): array
    {
        return [PushChannel::class];
    }

    public function toPush(object $notifiable): PushNotification
    {
        return new PushNotification(
            title: __('notifications.driver-offer.title'),
            body: __('notifications.driver-offer.body'),
        );
    }
}
