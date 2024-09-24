<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderStatusNotification implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(OrderStatusUpdated $event): void
    {
        // todo: send order status updated notifications
    }
}
