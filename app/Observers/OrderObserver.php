<?php

namespace App\Observers;

use App\Events\OrderPlaced;
use App\Events\OrderScheduleUpdated;
use App\Events\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderObserver
{
    public function creating(Order $order)
    {
        $order->number = strtoupper(uniqid('ORDER-'));
    }

    public function created(Order $order): void
    {
        OrderPlaced::dispatch($order);
    }

    public function updated(Order $order)
    {
        if ($order->isDirty('status')) {
            OrderStatusUpdated::dispatch($order, Auth::user());
        }

        if ($order->isDirty('scheduled_at')) {
            OrderScheduleUpdated::dispatch($order, Auth::user());
        }
    }
}
