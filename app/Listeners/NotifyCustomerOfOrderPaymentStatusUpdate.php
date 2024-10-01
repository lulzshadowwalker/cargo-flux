<?php

namespace App\Listeners;

use App\Events\OrderPaymentStatusUpdated;
use App\Notifications\OrderPaymentStatusUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyCustomerOfOrderPaymentStatusUpdate implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(OrderPaymentStatusUpdated $event): void
    {
        Notification::send(
            $event->order->customer,
            new OrderPaymentStatusUpdatedNotification($event->order)
        );
    }
}
