<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\User;
use App\Notifications\AdminOrderPlacedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyAdminsOfNewOrder implements ShouldQueue
{
    public function handle(OrderPlaced $event): void
    {
        Notification::send(
            User::admins()->get(),
            new AdminOrderPlacedNotification($event->order),
        );
    }
}
