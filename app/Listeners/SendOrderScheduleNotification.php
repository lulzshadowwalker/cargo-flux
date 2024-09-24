<?php

namespace App\Listeners;

use App\Events\OrderScheduleUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderScheduleNotification implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(OrderScheduleUpdated $event): void
    {
        // todo: send order schedule updated notifications
    }
}
