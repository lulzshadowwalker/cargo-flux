<?php

namespace App\Listeners;

use App\Events\DriverStatusUpdated;
use App\Notifications\DriverApprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyDriverOfStatusUpdate implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(DriverStatusUpdated $event): void
    {
        if ($event->driver->isApproved) {
            Notification::send(
                $event->driver,
                new DriverApprovedNotification($event->driver)
            );
            return;
        }

        Notification::send(
            $event->driver,
            new DriverApprovedNotification($event->driver)
        );
    }
}
